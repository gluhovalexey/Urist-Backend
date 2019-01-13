<?php 
namespace App\UristBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\UristBundle\Entity\Service;

/**
 * ServiceController
 *
 */
class ServiceController extends Controller
{

    /**
     * @Route("/api/service/entity-list", name="service_entity_list")
     * @Method({"GET"})
     * [EntityListAction Получить список сущностей услуг со всеми зависимостями]
     */
    public function EntityListAction()
    {
        $service = $this->getDoctrine()->getRepository('AppUristBundle:Service')->findAll();
        
        if (empty($service)) {
            $response = array(
                "code"    => 1,
                "message" => "Услуги не найдены",
                "error"   => null,
                "result"  => null
            );
            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $data = $this->get('jms_serializer')->serialize($service, 'json');

        $response = array(
            "code"    => 0,
            "message" => "success",
            "error"   => null,
            "result"  => json_decode($data)
        );
        return new JsonResponse($response, 200);
    }

    /**
     * @Route("/api/service/create", name="service_create")
     * @Method({"POST"})
     * [CreateService description]
     */
    public function CreateService(Request $request)
    {
        $service = new Service();
        $data = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();
        $category = $this->getDoctrine()->getRepository('AppUristBundle:Category')->getCategoryMulti($data["category"]);
        $service->setTitle($data["title"])
        ->setPrice($data["price"])
        ->setCategories($category)
        ;

        try{
            $em->persist($service);
            $em->flush();
            $response = array(
                'code'=>0,
                'message'=>'Service created',
                'errors'=>null,
                'result'=>null
            );

            return new JsonResponse($response, 201);

        } catch(\Doctrine\ORM\ORMException $e){

            return new JsonResponse($e->getMessage(), 500);
        }
    }

    /**
     * Редактирование сущности услуги
     * @Route("/api/service/edit/{slug}", name="service_edit")
     * @Method({"PUT"})
     * @param  [$request] запрос
     * @param  [$slug] slug идентификатор
     * @return [Json responce] ответ сервера
     */
    public function EditServiceAction(Request $request, $slug)
    {
        $data = json_decode($request->getContent(), true);
        $service = $this->getDoctrine()->getRepository('AppUristBundle:Service')->getService($slug);
        $category = $this->getDoctrine()->getRepository('AppUristBundle:Category')->getCategoryMulti($data["category"]);
        $service->setTitle($data["title"]);
        $service->setPrice($data["price"]);
        $service->syncCategories($category);
        $em = $this->getDoctrine()->getManager();        
        try{
            $em->persist($service);
            $em->flush();
            $response = array(
                'code'=>0,
                'message'=>'Услуга обновлена',
                'errors'=>null,
                'result'=>null
            );

            return new JsonResponse($response, 200);

        } catch(\Doctrine\ORM\ORMException $e){

            return new JsonResponse($e->getMessage(), 500);
        }
    }

    /**
     * Удаление сущности услуги
     * @Route("/api/service/delete/{slug}", name="service_remove")
     * @Method({"DELETE"})
     * @param  [slug] slug идентификатор
     * @return [type] ответ сервера
     */
    
    public function DeleteServiceAction($slug)
    {

        $service = $this->getDoctrine()->getRepository('AppUristBundle:Service')->getService($slug);
        $em = $this->getDoctrine()->getManager();
        try{
            $em->remove($service);
            $em->flush();
            $response = array(
                'code'=>0,
                'message'=>'Service deleted',
                'errors'=>null,
                'result'=>null
            );

            return new JsonResponse($response, 200);

        } catch(\Doctrine\ORM\ORMException $e){

            return new JsonResponse($e->getMessage(), 500);
        }
    }
}
