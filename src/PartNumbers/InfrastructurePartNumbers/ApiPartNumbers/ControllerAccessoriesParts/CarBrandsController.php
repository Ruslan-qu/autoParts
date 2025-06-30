<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\ControllerAccessoriesParts;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\Participant\DomainParticipant\AdaptersInterface\AdapterUserExtractionInterface;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\CarBrands;
use App\PartNumbers\InfrastructurePartNumbers\ErrorMessageViaSession\ErrorMessageViaSession;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormCarBrands\EditCarBrandsType;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormCarBrands\SaveCarBrandsType;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormCarBrands\SearchCarBrandsType;
use App\PartNumbers\ApplicationPartNumbers\QueryCarBrands\DTOQuery\DTOPartNameQuery\CarBrandsQuery;
use App\PartNumbers\ApplicationPartNumbers\QueryCarBrands\DeleteCarBrandsQuery\FindCarBrandsQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryCarBrands\SearchCarBrandsQuery\FindByCarBrandsQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryCarBrands\SearchCarBrandsQuery\SearchCarBrandsQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsCarBrands\DTOCommands\DTOCarBrandsCommand\CarBrandsCommand;
use App\PartNumbers\ApplicationPartNumbers\CommandsCarBrands\EditCarBrandsCommand\EditCarBrandsCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsCarBrands\SaveCarBrandsCommand\SaveCarBrandsCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryCarBrands\EditCarBrandsQuery\FindOneByIdCarBrandsQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsCarBrands\DeleteCarBrandsCommand\DeleteCarBrandsCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsCarBrands\DTOCommands\DTOCarBrandsObjCommand\CarBrandsObjCommand;

class CarBrandsController extends AbstractController
{
    /*Сохранения марки авто*/
    #[Route('/saveCarBrands', name: 'save_car_brands')]
    public function saveCarBrands(
        Request $request,
        SaveCarBrandsCommandHandler $saveCarBrandsCommandHandler,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {

        /* Форма сохранения */
        $form_save_car_brands = $this->createForm(SaveCarBrandsType::class);

        /*Валидация формы */
        $form_save_car_brands->handleRequest($request);

        $id = null;
        if ($form_save_car_brands->isSubmitted()) {
            if ($form_save_car_brands->isValid()) {

                try {

                    $participant = $adapterUserExtractionInterface->userExtraction();
                    $car_brands = $this->mapCarBrandsParticipant($form_save_car_brands->getData(), $participant);
                    $id = $saveCarBrandsCommandHandler
                        ->handler(new CarBrandsCommand($car_brands));
                } catch (HttpException $e) {

                    $errorMessageViaSession->errorMessageSession($e);
                }
            }
        }

        return $this->render('@сarBrands/saveCarBrands.html.twig', [
            'title_logo' => 'Добавление марки авто',
            'form_save_car_brands' => $form_save_car_brands->createView(),
            'id' => $id
        ]);
    }

    /*Поиск марки авто*/
    #[Route('searchCarBrands', name: 'search_car_brands')]
    public function searchCarBrands(
        Request $request,
        CarBrands $сarBrands,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        FindByCarBrandsQueryHandler $findByCarBrandsQueryHandler,
        SearchCarBrandsQueryHandler $searchCarBrandsQueryHandler,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {

        /*Форма поиска*/
        $form_search_car_brands = $this->createForm(SearchCarBrandsType::class);

        /*Валидация формы */
        $form_search_car_brands->handleRequest($request);

        try {

            $participant = $adapterUserExtractionInterface->userExtraction();
            $car_brands = $this->mapObjectCarBrands($сarBrands, $participant);
            $search_data = $findByCarBrandsQueryHandler->handler(new CarBrandsQuery($car_brands));
        } catch (HttpException $e) {

            $errorMessageViaSession->errorMessageSession($e);
        }

        if ($form_search_car_brands->isSubmitted()) {
            if ($form_search_car_brands->isValid()) {

                try {
                    $car_brands = $this->mapCarBrandsParticipant($form_search_car_brands->getData(), $participant);
                    $search_data = $searchCarBrandsQueryHandler
                        ->handler(new CarBrandsQuery($car_brands));
                } catch (HttpException $e) {

                    $errorMessageViaSession->errorMessageSession($e);
                }
            }
        }

        return $this->render('@сarBrands/searchCarBrands.html.twig', [
            'title_logo' => 'Поиск марки авто',
            'form_search_car_brands' => $form_search_car_brands->createView(),
            'search_data' => $search_data,

        ]);
    }

    /*Редактирования марки авто*/
    #[Route('editCarBrands', name: 'edit_car_brands')]
    public function editCarBrands(
        Request $request,
        AdapterUserExtractionInterface $adapterUserExtractionInterface,
        FindOneByIdCarBrandsQueryHandler $findOneByIdCarBrandsQueryHandler,
        EditCarBrandsCommandHandler $editCarBrandsCommandHandler,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {

        /*Форма Редактирования*/
        $form_edit_car_brands = $this->createForm(EditCarBrandsType::class);

        /*Валидация формы */
        $form_edit_car_brands->handleRequest($request);

        try {
            $participant = $adapterUserExtractionInterface->userExtraction();
        } catch (HttpException $e) {

            $errorMessageViaSession->errorMessageSession($e);

            return $this->redirectToRoute('search_car_brands');
        }

        if (empty($form_edit_car_brands->getData())) {

            $car_brands = $this->mapCarBrands($request->query->all()['id'], '', $participant);
            try {

                $data_form_edit_car_brands = $findOneByIdCarBrandsQueryHandler
                    ->handler(new CarBrandsQuery($car_brands));
            } catch (HttpException $e) {

                $errorMessageViaSession->errorMessageSession($e);

                return $this->redirectToRoute('search_car_brands');
            }
        }

        if (!empty($request->request->all())) {

            $data_form_edit_car_brands = $request->request->all()['edit_car_brands'];
        }

        $id = null;
        if ($form_edit_car_brands->isSubmitted()) {
            if ($form_edit_car_brands->isValid()) {

                $data_form_edit_car_brands = $request->request->all()['edit_car_brands'];
                $data_edit_car_brands = $this->mapCarBrands(
                    $form_edit_car_brands->getData()['id'],
                    $form_edit_car_brands->getData()['car_brand'],
                    $participant
                );

                try {

                    $id = $editCarBrandsCommandHandler
                        ->handler(new CarBrandsCommand($data_edit_car_brands));
                } catch (HttpException $e) {

                    $errorMessageViaSession->errorMessageSession($e);
                }
            }
        }

        return $this->render('@сarBrands/editCarBrands.html.twig', [
            'title_logo' => 'Изменение марки авто',
            'form_edit_car_brands' => $form_edit_car_brands->createView(),
            'id' => $id,
            'data_form_edit_car_brands' => $data_form_edit_car_brands
        ]);
    }

    /*Удаление марки авто*/
    #[Route('deleteCarBrands', name: 'delete_car_brands')]
    public function deleteCarBrands(
        Request $request,
        FindCarBrandsQueryHandler $findCarBrandsQueryHandler,
        DeleteCarBrandsCommandHandler $deleteCarBrandsCommandHandler,
        ErrorMessageViaSession $errorMessageViaSession
    ): Response {
        try {

            $car_brands = $findCarBrandsQueryHandler
                ->handler(new CarBrandsQuery($request->query->all()));

            $deleteCarBrandsCommandHandler
                ->handler(new CarBrandsObjCommand($car_brands));
            $this->addFlash('delete', 'марка авто удалена');
        } catch (HttpException $e) {

            $errorMessageViaSession->errorMessageSession($e);
        }

        return $this->redirectToRoute('search_car_brands');
    }

    private function mapCarBrandsParticipant(array $car_brands, Participant $participant): array
    {
        $car_brands['id_participant'] = $participant;

        return $car_brands;
    }

    private function mapObjectCarBrands(CarBrands $car_brands, Participant $participant): array
    {
        $arr_car_brands['id'] = $car_brands->getId();
        $arr_car_brands['car_brand'] = $car_brands->getCarBrand();
        $arr_car_brands['id_participant'] = $participant;

        return $arr_car_brands;
    }

    private function mapCarBrands($id = null, $car_brand = null, $participant = null): array
    {
        $arr_car_brands['id'] = $id;
        $arr_car_brands['car_brand'] = $car_brand;
        $arr_car_brands['id_participant'] = $participant;

        return $arr_car_brands;
    }
}
