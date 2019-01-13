<?php 
namespace App\UristBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\UristBundle\Entity\Category;


class CategoryController extends Controller
{

	/**
	 * @Route("/public/category/list", name="category_list")
	 * @Method({"GET"})
     *
	 */

    public function showCategoryListAction()
    {
    	$category = $this->getDoctrine()->getRepository('AppUristBundle:Category')->getCategoryList();

        // return new Response(
        //     '<html><body>Lucky number: '.$category.'</body></html>'
        // );
    	if (empty($category)) {
    		$response = array(
    			"code"    => 1,
    			"message" => "categories not found",
    			"error"   => null,
    			"result"  => null
    		);
            
    		return new JsonResponse($response, Response::HTTP_NOT_FOUND);        
    	}

    	$data = $category;

    	$response = array(
         "code"    => 0,
         "message" => "success",
         "error"   => null,
         "result"  => $data
     );

        return new Response(json_encode($response), 200, array('Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json')); 
    }

    /**
     * @Route("/public/category/{slug}", name="category_one")
     * @Method({"GET"})
     *
     */

    public function showCategoryAction($slug)
    {
        $category = $this->getDoctrine()->getRepository('AppUristBundle:Category')->getCategory($slug);
        
        if (empty($category)) {
            $response = array(
                "code"    => 1,
                "message" => "category not found",
                "error"   => null,
                "result"  => null
            );
            // return new JsonResponse($response, Response::HTTP_NO_CONTENT);
            return new Response(json_encode($response), 404, array('Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json')); 
        }

        $data = $this->get('jms_serializer')->serialize($category, 'json');

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
     * @Route("/api/category/create", name="category_create")
     * @Method({"POST"})
     * @return [type] ответ сервера
     */
    public function CreateCategoryAction(Request $request)
    {
        $data = $request->getContent();
        $category = $this->get('jms_serializer')->deserialize($data,'App\UristBundle\Entity\Category','json');
        $em = $this->getDoctrine()->getManager();
        try{
            $em->persist($category);
            $em->flush();
            $response = array(
                'code'=>0,
                'message'=>'Category created',
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
     * Удаление сущности категории
     * @Route("/api/category/delete/{slug}", name="category_remove")
     * @Method({"DELETE"})
     * @param  [slug] slug идентификатор
     * @return [type] ответ сервера
     */
    
    public function DeleteCategoryAction($slug)
    {

        $category = $this->getDoctrine()->getRepository('AppUristBundle:Category')->getCategory($slug);
        $em = $this->getDoctrine()->getManager();
        try{
            $em->remove($category);
            $em->flush();
            $response = array(
                'code'=>0,
                'message'=>'Category deleted',
                'errors'=>null,
                'result'=>null
            );

            return new JsonResponse($response, 200);

        } catch(\Doctrine\ORM\ORMException $e){

            return new JsonResponse($e->getMessage(), 500);
        }
    }

    /**    
    * @Route("/api/category/edit/{slug}", name="category_edit")
    * @Method({"PUT"})
    *
    * [EditCategoryAction Изменение сущности категории]
    * @param Request $request [запрос]
    * @param [type]  $slug    [slug идентификатор]
    */
     public function EditCategoryAction(Request $request, $slug)
     {
        $data = json_decode($request->getContent(), true);
        $category = $this->getDoctrine()->getRepository('AppUristBundle:Category')->getCategory($slug);
        $category->setTitle($data["title"]);

        $em = $this->getDoctrine()->getManager();
        try{
            $em->persist($category);
            $em->flush();
            $response = array(
                'code'=>0,
                'message'=>'Category updated',
                'errors'=>null,
                'result'=>null
            );

            return new JsonResponse($response, 200);

        } catch(\Doctrine\ORM\ORMException $e){

            return new JsonResponse($e->getMessage(), 500);
        }
    }
}