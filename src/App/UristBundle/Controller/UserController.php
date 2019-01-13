<?php 
namespace App\UristBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\UristBundle\Entity\User;
use App\UristBundle\Utils\UristUtils;

/**
 * UserController
 *
 */
class UserController extends Controller
{

    /**
     * @Route("/api/user/list", name="user_list")
     * @Method({"GET"})
     * [UserListAction Получить список сущностей пользователей]
     * @return [json] результат запроса
     */
    public function UserListAction()
    {
        $rolesNames = ["ROLE_SUPER_ADMIN"];
        $roles = $this->getDoctrine()->getRepository('AppUristBundle:Role')->findRolesByName($rolesNames);
        $user = $this->getDoctrine()->getRepository('AppUristBundle:User')->findUsersNoSpecRoles($roles);
        if (empty($user)) {
            $response = array(
                "code"    => 404,
                "message" => "Пользователи не найдены",
                "error"   => null,
                "result"  => null
            );
            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $data = $this->get('jms_serializer')->serialize($user, 'json');

        $response = array(
            "code"    => 0,
            "message" => "success",
            "error"   => null,
            "result"  => json_decode($data)
        );
        return new JsonResponse($response, 200);
    }

    /**
     * @Route("/api/user/create", name="user_create")
     * @Method({"POST"})
     * [CreateUser description]
     */
    public function CreateUser(Request $request)
    {
        $encoder = $this->container->get('security.password_encoder');
        $user = new User();
        $data = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();
        $password = UristUtils::generatePassword();
        $encodedPassword = $encoder->encodePassword($user, $password);
        $user->setUserName($data['username'])
             ->setEmail($data['email'])
             ->setPassword($encodedPassword)
        ;

        try{
            $em->persist($user);
            $em->flush();
            $response = array(
                'code'=>0,
                'message'=>'User created',
                'errors'=>null,
                'result'=>null
            );

            $message = (new \Swift_Message('Доступ на портал www.ius24.ru'));
            $message->setFrom('admin@ius24.ru')
                    ->setTo($data['email'])
                    ->setBody(
                        $this->renderView(
                        'AppUristBundle:Security:registration.html.twig',
                            array(
                                'name' => $data['username'],
                                'password' => $password
                            )
                        ),
                        'text/html'
                    )
            ;

            $this->get('mailer')->send($message);

            return new JsonResponse($response, 201);

        } catch(\Doctrine\ORM\ORMException $e){

            return new JsonResponse($e->getMessage(), 500);
        }
    }

    /**
     * Редактирование сущности пользователя
     * @Route("/api/user/edit/{id}", name="user_edit")
     * @Method({"PUT"})
     * @param  [$request] запрос
     * @param  [$id] id идентификатор
     * @return [Json responce] ответ сервера
     */
    public function EditUserAction(Request $request, $id)
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->getDoctrine()->getRepository('AppUristBundle:User')->getUser($id);
        $category = $this->getDoctrine()->getRepository('AppUristBundle:Category')->getCategoryMulti($data["category"]);
        $user->setTitle($data["title"]);
        $user->setPrice($data["price"]);
        $user->syncCategories($category);
        $em = $this->getDoctrine()->getManager();        
        try{
            $em->persist($user);
            $em->flush();
            $response = array(
                'code'=>0,
                'message'=>'Пользователь обновлен',
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
     * @Route("/api/user/delete/{id}", name="user_remove")
     * @Method({"DELETE"})
     * @param  [id] id идентификатор
     * @return [type] ответ сервера
     */
    
    public function DeleteUserAction($id)
    {

        $user = $this->getDoctrine()->getRepository('AppUristBundle:User')->find($id);
        $em = $this->getDoctrine()->getManager();
        try{
            $em->remove($user);
            $em->flush();
            $response = array(
                'code'=>0,
                'message'=>'User deleted',
                'errors'=>null,
                'result'=>null
            );

            return new JsonResponse($response, 200);

        } catch(\Doctrine\ORM\ORMException $e){

            return new JsonResponse($e->getMessage(), 500);
        }
    }
}
