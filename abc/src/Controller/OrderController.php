<?php

namespace App\Controller;

use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class OrderController extends AbstractController
{
    /**
     * @Route("/api/order", name="index", methods={"GET"})
     * @param UserInterface $user
     * @return Response
     */
    public function index(UserInterface $user)
    {
        try {
            $user_id = $user->getId();
            $repository = $this->getDoctrine()->getRepository(Order::class);
            $order = $repository->findBy(['user_id' => $user_id]);

            if (!$order) {
                return new JsonResponse(["message" => "Order not found !"]);
            }
            return new Response($this->json($order), Response::HTTP_OK);
        } catch (Exception $exception) {
            return new JsonResponse(["error: " => "Oops! Something went wrong..."]);
        }

    }

    public function create()
    {

    }

    /**
     * @Route("/api/order", name="store_order", methods={"POST"})
     * @param Request $request
     * @param UserInterface $user
     * @return Response
     */
    public function store(Request $request, UserInterface $user): Response
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
            $order->setUserId($user->getId());
            $order->setQuantity($request->get("quantity"));
            $entityManager->persist($order);
            $entityManager->flush();

            return new JsonResponse(["message" => "Your order has been received with order code " . $order->getOrderCode()]);
        } catch (\Exception $exception) {
            return new JsonResponse(["error: " => "Oops! Something went wrong..."]);
        }
    }

    /**
     * @Route("/api/order/{orderCode}", name="order_show", methods={"GET"})
     * @param $orderCode
     * @return object|string
     */
    public function show($orderCode)
    {
        try {
            $repository = $this->getDoctrine()->getRepository(Order::class);
            $order = $repository->findOneBy(['orderCode' => $orderCode]);

            if (!$order) {
                return new JsonResponse(["message" => "Order not found !"]);
            }
            return new Response($this->json($order), Response::HTTP_OK);
        } catch (\Exception $exception) {
            return new JsonResponse(["error: " => "Oops! Something went wrong..."]);
        }
    }

    public function edit()
    {

    }

    /**
     * @Route("/api/order/edit/{order_code}", name="order_update", methods={"PUT"})
     * @param Request $request
     * @param $order_code
     * @return Response
     */
    public function update(Request $request, $order_code): Response
    {
        try {
            $repository = $this->getDoctrine()->getRepository(Order::class);
            $order = $repository->findOneBy(['orderCode' => $order_code]);

            if (!$order) {
                return new JsonResponse(["message" => "Order not found !"]);
            }

            $date = date_create($order->getShippingDate());
            $shipping_date = date_format($date, 'Y-m-d');

            $now = new \DateTime();
            $now_date = $now->format("Y-m-d");

            if ($shipping_date < $now_date) {
                return new Response('Your shipping date of order is passed. You can not update ! Shipping date : ' . $shipping_date);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $my_order = $entityManager->getRepository(Order::class)->find($order->getId());

            $my_order->setShippingDate($request->get("shippingDate"));
            $my_order->setAddress($request->get("address"));
            $my_order->setProductId($request->get("product_id"));
            $my_order->setUserId($request->get("user_id"));
            $my_order->setQuantity($request->get("quantity"));

            $entityManager->flush();

            return new Response('Your order has been updated with this order code : ' . $order_code);
        } catch (\Exception $exception) {
            return new JsonResponse(["error: " => "Oops! Something went wrong..."]);
        }
    }

    public function destroy($order_code)
    {

    }

}
