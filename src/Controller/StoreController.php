<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Store;


/**
 * @Route("/api", name="api_")
 */
class StoreController extends AbstractController
{
    /**
     * @Route("/store", name="store_index", methods={"GET"})
     */
    public function index(): Response
    {
        $items = $this->getDoctrine()
            ->getRepository(Store::class)
            ->findAll();
 
        $data = [];
 
        foreach ($items as $item) {
           $data[] = [
               'id' => $item->getId(),
               'item' => $item->getItem(),
               'totalItems' => $item->getTotalItems(),
               'unit' => $item->getUnit(),
               'createDate' => $item->getCreateDate(),
           ];
        }
 
 
        return $this->json($data,200);
    }
 
    /**
     * @Route("/store", name="store_new", methods={"POST"})
     */
    public function new(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
 
        $store = new Store();
        $store->setItem($request->request->get('item'));
        $store->setTotalItems($request->request->get('total_items'));
        $store->setUnit($request->request->get('unit'));
        $store->setCreateDate(new \DateTime());
 
        $entityManager->persist($store);
        $entityManager->flush();
 
        return $this->json('Created new store successfully with id ' . $store->getId());
    }
 
    /**
     * @Route("/store/{id}", name="store_show", methods={"GET"})
     */
    public function show(int $id): Response
    {
        $store = $this->getDoctrine()
            ->getRepository(Store::class)
            ->find($id);
 
        if (!$store) {
 
            return $this->json('No store found for id' . $id, 404);
        }
 
        $data =  [
               'id' => $store->getId(),
               'item' => $store->getItem(),
               'totalItems' => $store->getTotalItems(),
               'unit' => $store->getUnit(),
               'createDate' => $store->getCreateDate(),
        ];
         
        return $this->json($data);
    }
 
    /**
     * @Route("/store/{id}", name="store_edit", methods={"PUT"})
     */
    public function edit(int $id, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $store = $entityManager->getRepository(Store::class)->find($id);
 
        if (!$store) {
            return $this->json('No store found for id' . $id, 404);
        }

        $store->setItem($request->request->get('item'));
        $store->setTotalItems($request->request->get('total_items'));
        $store->setUnit($request->request->get('unit'));
        $store->setCreateDate(new \DateTime());

        $entityManager->flush();
 
        $data =  [
            'id' => $store->getId(),
            'item' => $store->getItem(),
            'totalItems' => $store->getTotalItems(),
            'unit' => $store->getUnit(),
            'createDate' => $store->getCreateDate(),
        ];
         
        return $this->json($data);
    }
 
    /**
     * @Route("/store/{id}", name="store_delete", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $store = $entityManager->getRepository(Store::class)->find($id);
 
        if (!$store) {
            return $this->json('No store found for id' . $id, 404);
        }
 
        $entityManager->remove($store);
        $entityManager->flush();
 
        return $this->json('Deleted a store successfully with id ' . $id);
    }
 
 
}
