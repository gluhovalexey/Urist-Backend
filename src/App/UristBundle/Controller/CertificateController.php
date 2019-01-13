<?php 

namespace App\UristBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\UristBundle\Entity\Certificate;


class CertificateController extends Controller
{

	/**
     * Список сертификатов
	 * @Route("/public/certificate/list", name="certificate_list")
	 * @Method({"GET"})
	 */

    public function СertificateListAction()
    {
    	$certificate = $this->getDoctrine()->getRepository('AppUristBundle:Certificate')->findAll();
    	
    	if ( empty($certificate) ) {
    		$response = array(
    			"code"    => 1,
    			"message" => "Certificates not found",
    			"error"   => null,
    			"result"  => null
    		);
    		// return new JsonResponse($response, 404);
            return new Response(json_encode($response), 404, array('Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json')); 
    	}

    	$data = $this->get('jms_serializer')->serialize($certificate, 'json');

    	$response = array(
         "code"    => 0,
         "message" => "success",
         "error"   => null,
         "result"  => json_decode($data)
     );
    	// return new JsonResponse($response, 200);
        return new Response(json_encode($response), 200, array('Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json'));
    }


    /**
     * Загрузка сертификата
     * @Route("/api/certificate/upload", name="certificate_upload")
     * @Method({"POST"})
     * @param  [Object] Request
     * @return [type]
     */
    public function UploadСertificateAction(Request $request)
    {
        $file = $request->files->get('file');
        $certificate = new Certificate();
        $certificate->setFile($file);    
        $em = $this->getDoctrine()->getManager();
        try{
            $em->persist($certificate);
            $em->flush();
            $response = array(
                'code'=>0,
                'message'=>'Certificate uploaded',
                'errors'=>null,
                'result'=>null
            );

            return new JsonResponse($response, 201);

        } catch(\Doctrine\ORM\ORMException $e){

            return new JsonResponse($e->getMessage(), 500);
        }
    }
    
    /**
     *
     * Удаление сертификата
     * @Route("/api/certificate/delete/{slug}", name="certificate_delete")
     * @Method({"DELETE"})
     * @param  [type]
     * @return [type]
     */
    public function DeleteCertificateAction($slug)
    {

        $certificate = $this->getDoctrine()->getRepository('AppUristBundle:Certificate')->getCertificate($slug);
        $em = $this->getDoctrine()->getManager();
        try{
            $em->remove($certificate);
            $em->flush();
            $response = array(
                'code'=>0,
                'message'=>'Certificate deleted',
                'errors'=>null,
                'result'=>null
            );

            return new JsonResponse($response, 200);

        } catch(\Doctrine\ORM\ORMException $e){

            return new JsonResponse($e->getMessage(), 500);
        }
    }

    /**
     * Редактирование сущности сертификата
     * @Route("/api/certificate/edit/{slug}", name="certificate_edit")
     * @Method({"PUT"})
     * @param  [$request] запрос
     * @param  [$slug] slug идентификатор
     * @return [Json responce] ответ сервера
     */
    public function EditCertificateAction(Request $request, $slug)
    {
        $data = json_decode($request->getContent(), true);
        $certificate = $this->getDoctrine()->getRepository('AppUristBundle:Certificate')->getCertificate($slug);
        $certificate->setName($data["name"]);        
        $em = $this->getDoctrine()->getManager();
        try{
            $em->persist($certificate);
            $em->flush();
            $response = array(
                'code'=>0,
                'message'=>'Certificate updated',
                'errors'=>null,
                'result'=>null
            );

            return new JsonResponse($response, 200);

        } catch(\Doctrine\ORM\ORMException $e){

            return new JsonResponse($e->getMessage(), 500);
        }
    }
}