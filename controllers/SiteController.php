<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\core\Response;

use app\core\exceptions\NotFoundException;

use app\core\middlewares\AdminMiddleware;
use app\core\middlewares\AuthMiddleware;

use app\models\Contact;
use app\models\DbDonation;
use app\models\DbUser;

class SiteController extends Controller {

    public function __construct() {
        $this->registerMiddleware(new AdminMiddleware(['users']));
        $this->registerMiddleware(new AuthMiddleware(['profile']));

        $this->layout = self::LAYOUT_MAIN;
    }

    public function home() {
        return $this->render('home', [
            'name' => Application::$app->user->username ?? 'guest'
        ]);
    }

    public function profile() {
        return $this->render('profile', [
            'user' => Application::$app->user
        ]);
    }

    public function watermark(Request $request) {
        if ($request->isGet()) {
            return $this->render('watermark');
        }

        if ($request->isPost()) {
            try {
                Application::$app->watermarker->stampImage($request->getFile('image'));
            } catch (Exception $e) {
                $this->setFlash('error', $e->getMessage());

                return $this->render('watermark');
            }
        }
    }

    public function contact(Request $request, Response $response) {
        $contact = new Contact();

        if ($request->isPost()) {
            $contact->loadData($request->getBody());

            if ($contact->validate()) {
                if ($contact->send()) {
                    $this->setFlash('success', 'Thank you for contacting us. We will get in touch with you soon.');

                    $response->redirect('/contact');
                } else {
                    $this->setFlash('error', 'Something went wrong while submitting your message. Please try again later.');
                }
            }
        }

        return $this->render('contact', [
            'model' => $contact
        ]);
    }

    public function donate(Request $request, Response $response) {
        $donation = new DbDonation();

        if ($request->isPost()) {
            $donation->loadData($request->getBody());

            if ($donation->validate()) {
                if ($donation->save()) {
                    $mollieDonation = Application::$app->mollie->createDonation($donation->amount, DbDonation::lastInsertId());

                    header('Location: ' . $mollieDonation->getCheckoutUrl(), true, 303);

                    exit;
                }

                $this->setFlash('error', 'Something went wrong while processing your donation. Please try again later.');
            }
        }

        return $this->render('donate', [
            'model' => $donation
        ]);
    }

    public function mollie(Request $request) {
        $molliePaymentId = $request->getBody()['id'] ?? 0;

        if ($request->origin() !== 'https://www.mollie.com/' || empty($molliePaymentId)) {
            throw new NotFoundException();
        }

        $result = Application::$app->mollie->processDonation(intval($molliePaymentId));

        if (!$result) {
            // TODO: add logging
        }
    }

    public function thankYou(Request $request) {
        $donationId = $request->getBody()['donation_id'] ?? 0;

        if ($request->origin() !== 'https://www.mollie.com/' || empty($donationId)) {
            throw new NotFoundException();
        }

        return $this->render('donate_return', ['donationId' => $donationId]);
    }

    public function users(Request $request) {
        $pageNumber = $request->getBody()['p'] ?? 1;

        if (!is_int($pageNumber) && is_numeric($pageNumber)) {
            $pageNumber = intval($pageNumber);
        } else {
            $pageNumber = 1;
        }

        $index = $pageNumber - 1;

        $where = [
            'status' => [
                'value' => DbUser::STATUS_DELETED,
                'operator' => '!='
            ],
            'admin' => [
                'value' => false
            ]
        ];

        $users = DbUser::findArray($where, ['id', 'username', 'email', 'status', 'created_at'], $index, 'status', false);

        if (!empty($users)) {
            $rowCount = DbUser::countRows($where);

            $pageCount = ceil(intval($rowCount) / DbUser::queryLimit());

            $rowsLeft = intval($rowCount) - ($pageNumber) * DbUser::queryLimit();
        }

        return $this->render('users', [
            'users' => $users,
            'pageNumber' => $pageNumber,
            'pageCount' => $pageCount ?? 0,
            'rowsLeft' => $rowsLeft ?? 0
        ]);
    }

}