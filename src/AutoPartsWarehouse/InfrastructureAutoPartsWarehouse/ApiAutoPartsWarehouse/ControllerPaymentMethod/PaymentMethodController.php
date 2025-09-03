<?php

namespace App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\ControllerPaymentMethod;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Participant\DomainParticipant\AdaptersInterface\AdapterUserExtractionInterface;
use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormPaymentMethod\EditPaymentMethodType;
use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormPaymentMethod\SavePaymentMethodType;
use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormPaymentMethod\SearchPaymentMethodType;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsPaymentMethod\DTOCommands\DTOPaymentMethodCommand\PaymentMethodCommand;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsPaymentMethod\SavePaymentMethodCommand\SavePaymentMethodCommandHandler;

class PaymentMethodController extends AbstractController
{
    /*Сохранения Способ оплаты*/
    #[Route('saveMethod', name: 'save_method')]
    public function saveMethod(
        Request $request,
        SavePaymentMethodCommandHandler $savePaymentMethodCommandHandler,
        AdapterUserExtractionInterface $adapterUserExtractionInterface
    ): Response {

        /* Форма сохранения */
        $form_save_payment_method = $this->createForm(SavePaymentMethodType::class);

        /*Валидация формы */
        $form_save_payment_method->handleRequest($request);

        $id = null;
        if ($form_save_payment_method->isSubmitted()) {
            if ($form_save_payment_method->isValid()) {

                try {

                    $participant = $adapterUserExtractionInterface->userExtraction();
                    $payment_method = $this->mapPaymentMethod(null, $form_save_payment_method->getData()['$method'], $participant);
                    $id = $savePaymentMethodCommandHandler
                        ->handler(new PaymentMethodCommand($payment_method));
                } catch (HttpException $e) {

                    $this->errorMessageViaSession($e);
                }
            }
        }

        return $this->render('@paymentMethod/saveMethod.html.twig', [
            'title_logo' => 'Добавление Способ оплаты',
            'form_search_payment_method' => $form_save_payment_method->createView(),
            'id' => $id
        ]);
    }

    /*Поиск Способ оплаты*/
    #[Route('searchMethod', name: 'search_method')]
    public function searchMethod(
        Request $request,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        //FindAllPaymentMethodQueryHandler $findAllPaymentMethodQueryHandler,
        //FindByPaymentMethodQueryHandler $findByPaymentMethodQueryHandler,
        //SearchPaymentMethodQueryHandler $searchPaymentMethodQueryHandler
    ): Response {

        /*Форма поиска*/
        $form_search_payment_method = $this->createForm(SearchPaymentMethodType::class);

        /*Валидация формы */
        $form_search_payment_method->handleRequest($request);

        try {

            $participant = $adapterUserExtractionInterface->userExtraction();
            $payment_method = $this->mapPaymentMethod(null, null, $participant);
            $search_data = $findByPaymentMethodQueryHandler->handler(new PaymentMethodQuery($payment_method));
            //$search_data = $findAllPaymentMethodQueryHandler->handler();
        } catch (HttpException $e) {

            $this->errorMessageViaSession($e);
        }

        if ($form_search_payment_method->isSubmitted()) {
            if ($form_search_payment_method->isValid()) {

                try {
                    $payment_method = $this->mapPaymentMethod(
                        null,
                        $form_search_payment_method->getData()['method'],
                        $participant
                    );
                    $search_data = $searchPaymentMethodQueryHandler
                        ->handler(new PaymentMethodQuery($payment_method));
                } catch (HttpException $e) {

                    $this->errorMessageViaSession($e);
                }
            }
        }

        return $this->render('@paymentMethod/searchMethod.html.twig', [
            'title_logo' => 'Поиск Способ оплаты',
            'form_search_payment_method' => $form_search_payment_method->createView(),
            'search_data' => $search_data,

        ]);
    }

    /*Редактирования Способ оплаты*/
    #[Route('editMethod', name: 'edit_method')]
    public function editMethod(
        Request $request,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        //FindOneByIdPaymentMethodQueryHandler $findOneByIdPaymentMethodQueryHandler,
        //EditPaymentMethodCommandHandler $editPaymentMethodCommandHandler
    ): Response {

        /*Форма Редактирования*/
        $form_edit_payment_method = $this->createForm(EditPaymentMethodType::class);

        /*Валидация формы */
        $form_edit_payment_method->handleRequest($request);

        try {
            $participant = $adapterUserExtractionInterface->userExtraction();
        } catch (HttpException $e) {

            $this->errorMessageViaSession($e);

            return $this->redirectToRoute('search_method');
        }

        if (empty($form_edit_payment_method->getData())) {

            $payment_method = $this->mapPaymentMethod($request->query->all()['id'], null, $participant);
            try {

                $data_form_edit_payment_method = $findOneByIdPaymentMethodQueryHandler
                    ->handler(new PaymentMethodQuery($payment_method));
            } catch (HttpException $e) {

                $this->errorMessageViaSession($e);

                return $this->redirectToRoute('search_method');
            }
        }

        if (!empty($request->request->all())) {
            $data_form_edit_payment_method = $request->request->all()['edit_payment_method'];
        }

        $id = null;
        if ($form_edit_payment_method->isSubmitted()) {
            if ($form_edit_payment_method->isValid()) {

                $data_form_edit_payment_method = $request->request->all()['edit_payment_method'];
                $data_edit_payment_method = $this->mapPaymentMethod(
                    $form_edit_payment_method->getData()['id'],
                    $form_edit_payment_method->getData()['method'],
                    $participant
                );

                try {

                    $id = $editPaymentMethodCommandHandler
                        ->handler(new PaymentMethodCommand($data_edit_payment_method));
                } catch (HttpException $e) {

                    $this->errorMessageViaSession($e);
                }
            }
        }

        return $this->render('@paymentMethod/editMethod.html.twig', [
            'title_logo' => 'Изменение Способ оплаты',
            'form_edit_payment_method' => $form_edit_payment_method->createView(),
            'id' => $id,
            'data_form_edit_payment_method' => $data_form_edit_payment_method
        ]);
    }

    /*Удаление Способ оплаты*/
    #[Route('deleteMethod', name: 'delete_method')]
    public function deleteMethod(
        Request $request,
        //FindPaymentMethodQueryHandler $findPaymentMethodQueryHandler,
        // DeletePaymentMethodCommandHandler $deletePaymentMethodCommandHandler,
    ): Response {
        try {

            $payment_method = $findPaymentMethodQueryHandler
                ->handler(new PaymentMethodQuery($request->query->all()));

            $deletePaymentMethodCommandHandler
                ->handler(new PaymentMethodObjCommand($payment_method));
            $this->addFlash('delete', 'Способ оплаты удален');
        } catch (HttpException $e) {

            $this->errorMessageViaSession($e);
        }

        return $this->redirectToRoute('search_method');
    }

    private function errorMessageViaSession(HttpException $e): static
    {

        $arr_validator_errors = json_decode($e->getMessage(), true);

        /* Выводим сообщения ошибки в форму через сессии  */
        foreach ($arr_validator_errors as $key => $value_errors) {
            if (is_array($value_errors)) {
                foreach ($value_errors as $key => $value) {
                    $message = $value;
                    $propertyPath = $key;
                }
            } else {
                $message = $value_errors;
                $propertyPath = $key;
            }

            $this->addFlash($propertyPath, $message);
        }

        return $this;
    }

    private function mapPaymentMethod($id, $method, $participant): array
    {
        $arr_payment_method['id'] = $id;
        $arr_payment_method['method'] = $method;
        $arr_payment_method['id_participant'] = $participant;

        return $arr_payment_method;
    }
}
