<?php

namespace App\Controller;

use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/order", name="order")
     */
    public function index()
    {
        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
        ]);
    }

    /**
     * @Route("/order/create", name="create_order")
     * @param Request $request
     * @return Response
     */
    public function createOrder(Request $request): Response
    {
        try {
            // you can fetch the EntityManager via $this->getDoctrine()
            // or you can add an argument to the action: createProduct(EntityManagerInterface $entityManager)
            $entityManager = $this->getDoctrine()->getManager();

            $order = new Order();
            $order->setOrderCode($request->get("orderCode"));
            $order->setShippingDate($request->get("shippingDate"));
            $order->setAddress($request->get("address"));
            $order->setProductId($request->get("product_id"));
            $order->setUserId($request->get("user_id"));
            $order->setQuantity($request->get("quantity"));


            // tell Doctrine you want to (eventually) save the Product (no queries yet)
            $entityManager->persist($order);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return new Response('Saved your order with order code ' . $order->getOrderCode());
        } catch (\Exception $exception) {
            dd($exception->getMessage());
        }
    }

    /**
     * @Route("/order/{orderCode}/edit", name="order_edit")
     * @param Request $request
     * @return Response
     */
    public function editOrder(Request $request): Response
    {
        try {
            $repository = $this->getDoctrine()->getRepository(Order::class);
            $order = $repository->findOneBy(['orderCode' => $request->get("orderCode")]);

            if (!$order) {
                throw $this->createNotFoundException(
                    'No product found for order code ' . $order->getOrderCode()
                );
            }

            $date = date_create($order->getShippingDate());
            $order_date = date_format($date, 'Y-m-d');

            $now = new \DateTime();
            $now_date = $now->format("Y-m-d");


            if ($order_date > $now_date){
                return new Response('You could not edit your order! ' . $order_date);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $my_order = $entityManager->getRepository(Order::class)->find($order->getId());

            $my_order->setShippingDate($request->get("shippingDate"));
            $my_order->setAddress($request->get("address"));
            $my_order->setProductId($request->get("product_id"));
            $my_order->setUserId($request->get("user_id"));
            $my_order->setQuantity($request->get("quantity"));

            $entityManager->flush();

            return new Response('Updated your order with order code ' . $now_date);
        } catch (\Exception $exception) {
            dd($exception);
        }
    }

    /**
     * @Route("/order/{orderCode}", name="order_show")
     * @param $orderCode
     * @return object|string
     */
    public function show($orderCode)
    {
        $repository = $this->getDoctrine()->getRepository(Order::class);
        $order = $repository->findOneBy(['orderCode' => $orderCode]);

        if (!$order) {
            throw $this->createNotFoundException(
                'No product found for order code ' . $order->getOrderCode()
            );
        }

       dd($order);

    }

    /**
     * @Route("/orders/{user_id}", name="order_fetch")
     * @param $user_id
     * @return object|string
     */
    public function all($user_id)
    {
        $repository = $this->getDoctrine()->getRepository(Order::class);
        $order = $repository->findOneBy(['user_id' => $user_id]);

        if (!$order) {
            throw $this->createNotFoundException(
                'No product found for order code ' . $order->getOrderCode()
            );
        }

        dd($order);

    }
}
