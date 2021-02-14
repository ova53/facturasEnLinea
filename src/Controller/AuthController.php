<?php
 /**
  * Created by PhpStorm.
  * User: hicham benkachoud
  * Date: 06/01/2020
  * Time: 20:39
  */

 namespace App\Controller;


 use App\Entity\User;
 use App\Repository\UserRepository;
 use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
 use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 use Symfony\Component\HttpFoundation\JsonResponse;
 use Symfony\Component\HttpFoundation\Request;
 use Symfony\Component\HttpFoundation\Response;
 use Symfony\Component\Routing\Annotation\Route;
 use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
 use Symfony\Component\Security\Core\User\UserInterface;
 use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

 class AuthController extends AbstractController
 {
    // #[Route('/register', name: 'register')]
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $em = $this->getDoctrine()->getManager();
        $email = $request->get('email');
        $password = $request->get('password');
        // dd($request);
   if ( empty($password) || empty($email)){
    return $this->json([
        "Error" => true,
        "message" => "Invalid Username or Password or Email"], 400);
   }


   $user = new User($email);
   $user->setPassword($encoder->encodePassword($user, $password));
   $user->setEmail($email);
   $em->persist($user);
   $em->flush();
   return $this->json([
       "Error" => false,
       "message" => "Usuario creado exitosamente",
       "data"=> [
           "user" => $user->getEmail(),
           "id" => $user->getId()
       ]], 200);
  }

  /**
   * @param UserInterface $user
   * @param JWTTokenManagerInterface $JWTManager
   * @return JsonResponse
   */
    //   #[Route('/api/login', name: 'login')]
    /**
     * @Route("/login", name="login")
     */
  public function getTokenUser(Request $request, 
                               UserRepository $userRepository, 
                               EncoderFactoryInterface $factory, 
                               JWTTokenManagerInterface $JWTManager)
    {
        $headers = $request->headers->get('Authorization');
        $login = explode(" ", ($headers));
        $userAndPassword = (base64_decode($login[1]));
        $login2 = explode(":", $userAndPassword);
        $email = $login2[0];
        $password = $login2[1];
        dd($email);
        $user = $userRepository->findOneBy(["email" => $email]);

    if (!$user) {
        return $this->json([
            "Error" => true,
            "message" => "Usuario no existe",
            ], 400);
    } 

    $encoder = $factory->getEncoder($user);
    if ($encoder->isPasswordValid($user->getPassword(),
    $password,
    $user->getSalt())) {
        return 
        $this->json(['token' => $JWTManager->create($user)]);
    } else {
        return $this->json([
            "Error" => true,
            "message" => "Usuario y contraseña invalidos",
            ], 400);
    }

  }

 }
