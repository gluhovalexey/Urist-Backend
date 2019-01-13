<?php 

namespace App\UristBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Filesystem\Filesystem;

use App\UristBundle\Entity\Document;
use App\UristBundle\Utils\UristUtils;


class DocumentController extends Controller
{

	/**
	 * @Route("/public/document/list", name="document_list")
	 * @Method({"GET"})
     *
	 */

    public function DocumentListAction()
    {
    	$document = $this->getDoctrine()->getRepository('AppUristBundle:Document')->getDocumentList();
    	
    	if (empty($document)) {
    		$response = array(
    			"code"    => 1,
    			"message" => "documents not found",
    			"error"   => null,
    			"result"  => null
    		);
    		//return new JsonResponse($response, Response::HTTP_NOT_FOUND);
            return new Response(json_encode($response), 404, array('Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json')); 
    	}

    	$data = $this->get('jms_serializer')->serialize($document, 'json');

    	$response = array(
         "code"    => 0,
         "message" => "success",
         "error"   => null,
         "result"  => json_decode($data)
     );
    	//return new JsonResponse($response, 200);
        return new Response(json_encode($response), 200, array('Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json')); 
    }

    /**
     * @Route("/api/document/entity-list", name="document_entity_list")
     * @Method({"GET"})
     * [EntityListAction Получить список сущностей документов со всеми зависимостями]
     */
    public function EntityListAction()
    {
        $document = $this->getDoctrine()->getRepository('AppUristBundle:Document')->findAll();
        
        if (empty($document)) {
            $response = array(
                "code"    => 1,
                "message" => "documents not found",
                "error"   => null,
                "result"  => null
            );
            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $data = $this->get('jms_serializer')->serialize($document, 'json');

        $response = array(
            "code"    => 0,
            "message" => "success",
            "error"   => null,
            "result"  => json_decode($data)
        );
        return new JsonResponse($response, 200);
    }


    /**
     * Загрузка документа
     * @Route("/api/document/upload", name="document_upload")
     * @Method({"POST"})
     * @param  [Object] Request
     * @return [type]
     */
    public function UploadDocumentAction(Request $request)
    {
        $file = $request->files->get('file');
        $document = new Document();
        $document->setFile($file);
        $category =  $this->getDoctrine()->getRepository('AppUristBundle:Category')->getCategoryMulti(json_decode($request->request->get('category')));
        $document->setCategories($category);
        $em = $this->getDoctrine()->getManager();
        $em->persist($document);
        $em->flush();
        return new JsonResponse([], 201);
    }
    
    /**
     *
     * Удаление сущности доумента
     * @Route("/api/document/delete/{slug}", name="document_remove")
     * @Method({"DELETE"})
     * @param  [type]
     * @return [type]
     */
    public function DeleteDocumentAction($slug)
    {

        $document = $this->getDoctrine()->getRepository('AppUristBundle:Document')->getDocument($slug);

        $em = $this->getDoctrine()->getManager();
        try{
            $em->remove($document);
            $em->flush();
            $response = array(
                'code'=>0,
                'message'=>'Document deleted',
                'errors'=>null,
                'result'=>null
            );

            return new JsonResponse($response, 200);

        } catch(\Doctrine\ORM\ORMException $e){

            return new JsonResponse($e->getMessage(), 500);
        }
    }

    /**
     * Редактирование сущности документа
     * @Route("/api/document/edit/{slug}", name="document_edit")
     * @Method({"PUT"})
     * @param  [$request] запрос
     * @param  [$slug] slug идентификатор
     * @return [Json responce] ответ сервера
     */
    public function EditDocumentAction(Request $request, $slug)
    {
        $data = json_decode($request->getContent(), true);
        $document = $this->getDoctrine()->getRepository('AppUristBundle:Document')->getDocument($slug);
        $category = $this->getDoctrine()->getRepository('AppUristBundle:Category')->getCategoryMulti($data["category"]);
        $document->setName($data["name"]);
        $document->syncCategories($category);
        $em = $this->getDoctrine()->getManager();
        try{
            $em->persist($document);
            $em->flush();
            $response = array(
                'code'=>0,
                'message'=>'Document updated',
                'errors'=>null,
                'result'=>null
            );

            return new JsonResponse($response, 200);

        } catch(\Doctrine\ORM\ORMException $e){

            return new JsonResponse($e->getMessage(), 500);
        }
    }
}