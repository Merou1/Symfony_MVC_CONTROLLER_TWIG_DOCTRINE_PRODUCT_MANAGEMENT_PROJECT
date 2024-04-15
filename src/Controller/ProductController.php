<?php 
// src/Controller/ProductController.php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;



class ProductController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
{
    $this->entityManager = $entityManager;
}
    #[Route('/products', name: 'product_index', methods: ['GET'])]
    public function index(): Response
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();

        return $this->render('index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/products/create', name: 'product_create', methods: ['GET'])]
    public function create(): Response
    {
        return $this->render('create.html.twig');
    }

    #[Route('/products/{id}/edit', name: 'product_edit', methods: ['GET'])]
    public function edit(Product $product): Response
    {
        return $this->render('edit.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/products/{id}/delete',name:'delete_product',methods:['GET'])]
    public function deleteProduct(Product $product):Response
    {
        return $this->render('delete.html.twig',parameters:['product'=>$product]);
    }

    #[Route('/products', name: 'product_store', methods: ['POST'])]
    public function store(Request $request): Response
    {
                // nakhd data men formulaire
        $name = $request->request->get('name');
        $price = $request->request->get('price');

            // product jdid
        $product = new Product();
        $product->setName($name);
        $product->setPrice($price);

        // persist+flush pour sauvgarder f l bd
        $this->entityManager->persist($product);//Hey Doctrine, pay attention to this object because I might want to save it to the database late
        $this->entityManager->flush();//When you call flush(), Doctrine looks at all the changes you've made via persist(), remove(), etc., and executes the necessary SQL queries to apply those changes to the database.

        return $this->redirectToRoute('product_index');
    }

#[Route('/products/{id}', name: 'product_update', methods: ['PUT'])]
public function update(Request $request, Product $product): Response //$product li tinstancia t instancia ela 7ssab id li f route bash nakhdu produit exact li tmodidfia
{
 
    $name = $request->request->get('name');
    $price = $request->request->get('price');

          // ndedlou propriétés dyal produit
    $product->setName($name);
    $product->setPrice($price);

    $this->entityManager->flush();      // flush bash properties itbedlou f bd


    return $this->redirectToRoute('product_index');
}

#[Route('/products/{id}', name: 'product_delete', methods: ['DELETE'])]
public function delete(Product $product): Response
{
    // remove bash it7eyed men bd
    $this->entityManager->remove($product);
    $this->entityManager->flush();

    return $this->redirectToRoute('product_index');
}

}

?>