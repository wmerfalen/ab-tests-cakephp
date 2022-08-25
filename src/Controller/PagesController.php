<?php

declare(strict_types=1);

/**
* CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
* Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
*
* Licensed under The MIT License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
* @link      https://cakephp.org CakePHP(tm) Project
* @since     0.2.9
* @license   https://opensource.org/licenses/mit-license.php MIT License
*/

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\View\Exception\MissingTemplateException;

use App\Model\Table\ConversionTable;
use App\Model\Table\VisitsTable;
use App\Model\Table\ControlSwitchTable;

/**
* Static content controller
*
* This controller will render views from templates/Pages/
*
* @link https://book.cakephp.org/4/en/controllers/pages-controller.html
*/
class PagesController extends AppController
{
    private const SALT = '63badbc773703c0850e69793f6def3b497492119fe4b477cf5d5c6bf72117be6149ec4fcb7bab78a6f7bcc5b8c2c9c1d6602b5850698841ae012ac9ab';
    public static function session_initialize()
    {
        Configure::write('Session', [
            'defaults' => 'php',
            'ini' => [
                // Get gc_maxlifetime and if not use 24 minutes
                'session.cookie_lifetime' => ini_get('session.gc_maxlifetime') ?? '1440',
            ]
        ]);
    }
    public function get_conversions_by_test(string $color): int
    {
        $c = new ConversionTable();
        $count = $c->query()
          ->where([
              'choice' => $color,
          ])->count();
        return $count;
    }


    public function gather_stats(string $client_ip)
    {
        return [
            'blue_visitors' => $this->get_conversions_by_test('blue'),
            'green_visitors' => $this->get_conversions_by_test('green'),
            'visitor_count' => 0,
            'client_traffic_percentage' => 0,
            'total_visits' => 0,
        ];
    }
    /**
    * Displays a view
    *
    * @param string ...$path Path segments.
    * @return \Cake\Http\Response|null
    * @throws \Cake\Http\Exception\ForbiddenException When a directory traversal attempt.
    * @throws \Cake\View\Exception\MissingTemplateException When the view file could not
    *   be found and in debug mode.
    * @throws \Cake\Http\Exception\NotFoundException When the view file could not
    *   be found and not in debug mode.
    * @throws \Cake\View\Exception\MissingTemplateException In debug mode.
    */
    public function display(string ...$path): ?Response
    {
        dd($this->gather_stats($_SERVER['REMOTE_ADDR']));
        static::session_initialize();
        if (!$path) {
            return $this->redirect('/');
        }
        if (in_array('..', $path, true) || in_array('.', $path, true)) {
            throw new ForbiddenException();
        }
        $page = $subpage = null;

        if (!empty($path[0])) {
            $page = $path[0];
        }
        if (!empty($path[1])) {
            $subpage = $path[1];
        }

        $this->set(compact('page', 'subpage'));
        $this->set('color', $this->decide_color());

        $this->log_visit($_SERVER['REMOTE_ADDR']);

        try {
            /**
            * If url is server.com/pages/a/bcd,
            * implode('/', $path) will be 'a/bcd'
            */
            return $this->render(implode('/', $path));
        } catch (MissingTemplateException $exception) {
            if (Configure::read('debug')) {
                throw $exception;
            }
            throw new NotFoundException();
        }
    }

    protected function sesh($key, $value = null)
    {
        if (null !== $value) {
            $this->request->getSession()->write($key, $value);
            return;
        }
        return $this->request->getSession()->read($key);
    }

    public function decide_color(): string
    {
        $color = $this->sesh('color');
        if (null === $color) {
            /**
            * This might not scale well.
            * A better option would be to have a pool of
            * objects that are ready to be acquired.
            * For low traffic, this is fine, but higher levels
            * of traffic may see race conditions.
            *
            * If the A/B campaign is for registered users,
            * the best idea is to associate the A/B test
            * as a new column on the user's table.
            * That way we can assign exactly which test a user
            * gets beforehand.
            */
            $switch = new ControlSwitchTable();
            $rows = $switch->find()->first();
            $color = $rows->test_value;
            $switch
            ->query()
            ->update()
            ->set(
                [
                /** Using 'static' for late static binding */
                'test_value' => $color === static::$test_A ? static::$test_B : static::$test_A
                ]
            )
            ->execute();
            $this->sesh('color', $color);
        }

        return $color;
    }

    /**
     * Use this as a cookie value to track the user without
     * exposing the primary key on our conversions table.
     * Makes it less likely for people to brute force the
     * cookie value and mess up our data.
     *
     * NOTE: this isn't really used in this iteration.
     */
    public function hash_client(string $client_id): string
    {
        return hash_hmac('sha512', $client_id, self::SALT);
    }

    public function conversion()
    {
        $this->log_conversion($_SERVER['REMOTE_ADDR']);
        $c = new ConversionTable();
        $c->query()
            ->insert(['ip','choice','hash','created'])
            ->values([
                'ip' => $_SERVER['REMOTE_ADDR'],
                'choice' => $_GET['choice'] ?? null,
                /**
                 * Ideally, you'd pass in more than just the IP address. Maybe
                 * the user agent and other identifying data.
                 */
                'hash' => $this->hash_client($_SERVER['REMOTE_ADDR']),
                'created' => date('Y-m-d H:i:s'),
            ])->execute();
        $this->set('color', $this->decide_color());
    }

    protected function log_conversion(string $client)
    {
        /**
         * This does not scale well. It would be a lot better to
         * save the visit to a fast memory mechanism, or fire off
         * an event so that a handler can take those things into
         * consideration and log it.
         */
        $v = new VisitsTable();
        $v->query()
            ->insert(['ip', 'converted', 'created'])
            ->values([
                'ip' => $client,
                'converted' => '0',
                'created' => date('Y-m-d H:i:s'),
         ])->execute();
    }
    protected function log_visit(string $client)
    {
        /**
         * This does not scale well. It would be a lot better to
         * save the visit to a fast memory mechanism, or fire off
         * an event so that a handler can take those things into
         * consideration and log it.
         */
        $v = new VisitsTable();
        $v->query()
            ->insert(['ip', 'converted', 'created'])
            ->values([
                'ip' => $client,
                'converted' => '0',
                'created' => date('Y-m-d H:i:s'),
         ])->execute();
    }
}
