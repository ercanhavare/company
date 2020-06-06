<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    /**
     * @Route("/product", name="product")
     */
    public function index()
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
     * @Route("/product/create", name="create_product")
     */
    public function createProduct(): Response
    {

        try {
            // you can fetch the EntityManager via $this->getDoctrine()
            // or you can add an argument to the action: createProduct(EntityManagerInterface $entityManager)
            $entityManager = $this->getDoctrine()->getManager();

            $product = new Product();
            $product->setName('Keyboard');
            $product->setPrice(1999);
            $product->setDescription('Ergonomic and stylish!');

            // tell Doctrine you want to (eventually) save the Product (no queries yet)
            $entityManager->persist($product);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return new Response('Saved new product with id ' . $product->getId());
        } catch (\Exception $exception) {
            dd($exception->getMessage());
        }

    }
}
