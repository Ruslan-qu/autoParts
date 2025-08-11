<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\ControllerPartNumbers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\Participant\DomainParticipant\AdaptersInterface\AdapterUserExtractionInterface;
use App\PartNumbers\InfrastructurePartNumbers\ErrorMessageViaSession\ErrorMessageViaSession;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\ReplacingOriginalNumbers;

class ReplacingOriginalNumbersController extends AbstractController
{
    /*Сохранения оригиналного номера детали*/
    #[Route('/saveReplacingOriginalNumber', name: 'save_replacing_original_number')]
    public function saveReplacingOriginalNumber(
        Request $request,
        //SaveReplacingOriginalNumbersCommandHandler $saveReplacingOriginalNumbersCommandHandler,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {

        /* Форма сохранения */
        $form_save_replacing_original_numbers = $this->createForm(SaveReplacingOriginalNumbersType::class);

        /*Валидация формы */
        $form_save_replacing_original_numbers->handleRequest($request);

        $id = null;
        if ($form_save_replacing_original_numbers->isSubmitted()) {
            if ($form_save_replacing_original_numbers->isValid()) {

                try {

                    $participant = $adapterUserExtractionInterface->userExtraction();
                    $replacing_original_numbers = $this->mapReplacingOriginalNumbers(
                        null,
                        $form_save_replacing_original_numbers->getData()['replacing_original_number'],
                        $form_save_replacing_original_numbers->getData()['original_manufacturer'],
                        $participant
                    );


                    $id = $saveReplacingOriginalNumbersCommandHandler
                        ->handler(new ReplacingOriginalNumbersCommand($replacing_original_numbers));
                } catch (HttpException $e) {

                    $errorMessageViaSession->errorMessageSession($e);
                }
            }
        }

        return $this->render('@replacingOriginalNumbers/saveReplacingOriginalNumber.html.twig', [
            'title_logo' => 'Добавление оригиналного номера',
            'form_save_replacing_original_numbers' => $form_save_replacing_original_numbers->createView(),
            'id' => $id
        ]);
    }

    /*Поиск оригиналного номера детали*/
    #[Route('searchReplacingOriginalNumber', name: 'search_replacing_original_number')]
    public function searchReplacingOriginalNumber(
        Request $request,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        // FindByReplacingOriginalNumbersQueryHandler $findByReplacingOriginalNumbersQueryHandler,
        //  SearchReplacingOriginalNumbersQueryHandler $searchReplacingOriginalNumbersQueryHandler,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {

        /*Форма поиска*/
        $form_search_replacing_original_numbers = $this->createForm(SearchOReplacingOriginalNumbersType::class);

        /*Валидация формы */
        $form_search_replacing_original_numbers->handleRequest($request);
        $search_data = $findByReplacingOriginalNumbersQueryHandler->handler();
        if ($form_search_replacing_original_numbers->isSubmitted()) {
            if ($form_search_replacing_original_numbers->isValid()) {

                try {
                    $participant = $adapterUserExtractionInterface->userExtraction();
                    $replacing_original_numbers = $this->mapReplacingOriginalNumbersParticipant(
                        $form_search_replacing_original_numbers->getData(),
                        $participant
                    );
                    $search_data = $searchReplacingOriginalNumbersQueryHandler
                        ->handler(new ReplacingOriginalNumbersQuery($replacing_original_numbers));
                } catch (HttpException $e) {

                    $errorMessageViaSession->errorMessageSession($e);
                }
            }
        }

        return $this->render('@replacingOriginalNumbers/searchReplacingOriginalNumber.html.twig', [
            'title_logo' => 'Поиск оригиналного номера детали',
            'form_search_replacing_original_numbers' => $form_search_replacing_original_numbers->createView(),
            'search_data' => $search_data,

        ]);
    }

    /*Редактирования оригиналного номера детали*/
    #[Route('editReplacingOriginalNumber', name: 'edit_replacing_original_number')]
    public function editReplacingOriginalNumber(
        Request $request,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        //FindOneByIdReplacingOriginalNumbersQueryHandler $findOneByIdReplacingOriginalNumbersQueryHandler,
        //EditReplacingOriginalNumbersCommandHandler $editReplacingOriginalNumbersCommandHandler,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {

        /*Форма Редактирования*/
        $form_edit_replacing_original_numbers = $this->createForm(EditReplacingOriginalNumbersType::class);

        /*Валидация формы */
        $form_edit_replacing_original_numbers->handleRequest($request);

        try {
            $participant = $adapterUserExtractionInterface->userExtraction();
        } catch (HttpException $e) {

            $errorMessageViaSession->errorMessageSession($e);

            return $this->redirectToRoute('search_replacing_original_number');
        }

        if (empty($form_edit_replacing_original_numbers->getData())) {

            $replacing_original_numbers = $this->mapReplacingOriginalNumbers(
                $request->query->all()['id'],
                null,
                null,
                $participant
            );

            try {

                $data_form_edit_replacing_original_numbers = $findOneByIdReplacingOriginalNumbersQueryHandler
                    ->handler(new ReplacingOriginalNumbersQuery($replacing_original_numbers));
            } catch (HttpException $e) {

                $errorMessageViaSession->errorMessageSession($e);

                return $this->redirectToRoute('search_replacing_original_number');
            }
        }

        if (!empty($request->request->all())) {
            $data_form_edit_replacing_original_numbers = $request->request->all()['edit_replacing_original_numbers'];
        }

        $id = null;
        if ($form_edit_replacing_original_numbers->isSubmitted()) {
            if ($form_edit_replacing_original_numbers->isValid()) {

                $data_form_edit_replacing_original_numbers = $request->request->all()['edit_replacing_original_numbers'];
                $data_edit_replacing_original_numbers = $this->mapReplacingOriginalNumbers(
                    $form_edit_replacing_original_numbers->getData()['id'],
                    $form_edit_replacing_original_numbers->getData()['replacing_original_number'],
                    $form_edit_replacing_original_numbers->getData()['original_manufacturer'],
                    $participant
                );

                try {

                    $id = $editReplacingOriginalNumbersCommandHandler
                        ->handler(new ReplacingOriginalNumbersCommand($data_edit_replacing_original_numbers));
                } catch (HttpException $e) {

                    $errorMessageViaSession->errorMessageSession($e);
                }
            }
        }

        return $this->render('@replacingOriginalNumbers/editReplacingOriginalNumber.html.twig', [
            'title_logo' => 'Изменение оригиналного номера детали',
            'form_edit_replacing_original_numbers' => $form_edit_replacing_original_numbers->createView(),
            'id' => $id,
            'data_form_edit_replacing_original_numbers' => $data_form_edit_replacing_original_numbers
        ]);
    }

    /*Удаление оригиналного номера детали*/
    #[Route('deleteReplacingOriginalNumber', name: 'delete_original_number')]
    public function deleteOriginalNumber(
        Request $request,
        //FindReplacingOriginalNumbersQueryHandler $findReplacingOriginalNumbersQueryHandler,
        // DeleteReplacingOriginalNumbersCommandHandler $deleteReplacingOriginalNumbersCommandHandler,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {
        try {

            $replacing_original_numbers = $findReplacingOriginalNumbersQueryHandler
                ->handler(new ReplacingOriginalNumbersQuery($request->query->all()));

            $deleteReplacingOriginalNumbersCommandHandler
                ->handler(new ReplacingOriginalNumbersObjCommand($replacing_original_numbers));
            $this->addFlash('delete', 'Оригиналный номер детали удален');
        } catch (HttpException $e) {

            $errorMessageViaSession->errorMessageSession($e);
        }

        return $this->redirectToRoute('search_replacing_original_number');
    }

    private function mapReplacingOriginalNumbersParticipant(array $replacing_original_numbers, Participant $participant): array
    {
        $replacing_original_numbers['id_participant'] = $participant;

        return $replacing_original_numbers;
    }

    private function mapReplacingOriginalNumbers(
        $id,
        $replacing_original_number,
        $original_manufacturer,
        $participant
    ): array {
        $arr_replacing_original_numbers['id'] = $id;
        $arr_replacing_original_numbers['replacing_original_number'] = $replacing_original_number;
        $arr_replacing_original_numbers['original_manufacturer'] = $original_manufacturer;
        $arr_replacing_original_numbers['id_participant'] = $participant;

        return $arr_replacing_original_numbers;
    }
}
