<?php 
namespace App\UristBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class MessageController extends Controller
{

	/**
	 * @Route("/public/message/send", name="message_send")
	 * @Method({"POST"})
     *
	 */

    public function sendMessageAction(Request $request)
    {
        $httpOrigin = $request->server->get('HTTP_ORIGIN');
        // $originAccess = ['http://www.ius24.ru', "http://ius24.ru", "http://localhost:4200"];
        $originAccess = ['http://www.ius24.ru', "http://ius24.ru"];

        // Защита от сторонних http клиентов
        if ( in_array($httpOrigin, $originAccess) ) {
            $data = json_decode($request->getContent(), true);
            if (empty($data)) {
                $response = array(
                    "code"    => 1,
                    "message" => "message is empty",
                    "error"   => null,
                    "result"  => null
                );

                return new Response(json_encode($response, 500));
            }

            $message = (new \Swift_Message('Сообщение от пользователя сайта www.ius24.ru'));
            $message->setFrom($data['email'])
            ->setTo('admin@ius24.ru')
            ->setBody(
               $this->renderView(
                        'AppUristBundle:Message:feedback.html.twig',
                            array(
                                'name' => $data['name'],
                                'phone' => $data['phone'],
                                'email' => $data['email'],
                                'message' => $data['message']
                            )
                        ),
                        'text/html'
            )
            ;
            $this->get('mailer')->send($message);

            $response = array(
               "code"    => 0,
               "message" => "success",
               "error"   => null,
               "result"  => null
           );
            return new Response(json_encode($response), 200, array('Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json'));
        }

        return new Response('error', 500);
    }
}