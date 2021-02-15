<?php

namespace App\Controller;

use App\Repository\FacturaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FacturasController extends AbstractController
{

    // Servicio de consulta facturas
    // #[Route('/api/facturas', name: 'facturas')]
    /**
     * @Route("/api/facturas", name="facturas")
     */
    public function index(FacturaRepository $facturaRepository): Response
    {
        // Recibo el parametro de consulta por get
        // $resourceType = $_REQUEST['num_doc'];

        $resourceType = $_GET['num_doc'] ? $_GET['num_doc'] : null;

        // validación de consulta 
        if (isset($resourceType)) {
            // Valido que el parametro exista en base de datos 
            $factura = $facturaRepository->findOneby(["numDoc" => $resourceType]);
            $numDoc = $factura->getNumDoc();
            // dd($_REQUEST);
            if (isset($numDoc)) {
                return $this->json([
                    'Error' => false,
                    'message' => 'Factura no encontrada',
                ], 206);
            }
            // Valido si la factura ya esta paga
            if($factura->getAutorization() != null) {
                return $this->json([
                    "Error" => true,
                    "message" => "La factura ya se encuentra paga",
                ], 406);
            }
            return $this->json([
                "Error" => false,
                "message" => "Factura encontrada",
                "data" => [
                    "id" => $factura->getId(),
                    "numDoc"=> $factura->getNumDoc(),
                    "name"=> $factura->getName(),
                    "amout"=> $factura->getAmout(),
                    "email"=> $factura->getEmail(),
                    "bill"=> $factura->getBill(),
                    "autorization"=> null
                ]
                ], 200);
        }
        // Mensaje retornado cuando no se envia factura en el parametro
        return $this->json([
            'Error' => false,
            'message' => '0k',
        ], 200);
    } 



    // #[Route('/api/confirmation', name: 'confirmation')]
    /**
     * @Route("/api/confirmation", name="confirmation")
     */
    public function confirmation(FacturaRepository $facturaRepository): Response
    {
        $em = $this->getDoctrine()->getManager();
        $confirData = $_REQUEST;
        $bill = $_REQUEST['bill'];
        $codeAproval = $_REQUEST['x_approval_code'];
        
        if ($codeAproval) {
            $Autorization = $facturaRepository->findOneBy(["bill"=>$bill]);
            $Autorization->setAutorization($codeAproval);
            $Autorization->setNumDoc($_REQUEST['num_doc']);
            $Autorization->setName($_REQUEST['name']);
            $Autorization->setAmout($_REQUEST['amount']);
            $Autorization->setEmail($_REQUEST['email']);
            $Autorization->setBill($bill);
            $em->persist($Autorization);
            $em->flush();
            return $this->json([
                "Error" => false,
                "message" => "Factura actulizada correctamente",
                "description" => [
                    "bill" => "factura actualizada con el id"." ".$bill,
                    "Autorizacipon" => $codeAproval
                ],
                "data" => $confirData    
            ], 200);
                
        }
        

        }

    }



