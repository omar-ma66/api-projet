<?php

namespace App\Controller;
use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api', name: 'api_')]
final class BookController extends AbstractController
{
  
    public function __construct(
                private readonly BookRepository $bookRepository ,
                private readonly EntityManagerInterface $entityManager,
                private readonly SerializerInterface $serializer
                ) {}


 #[route('/books',name:'books_index',methods:['GET'])]                
    public function index(): JsonResponse
    {

     $books = $this->bookRepository->findAll();
        return $this->json([
          'books'=>$books
        ]);
    }

 #[route('/books/{id}',name:'book_show',methods:['GET'],requirements:['id'=>'\d'])]   
 public function show(Book $book):JsonResponse
 {
    // $book = $this->bookRepository->find($id);
            return $this->json(['book'=>$book],context:['groups'=>'book:read'],status:200);

 }
 #[route('/books',name:'books_new',methods:['POST'])]
 public function create(Request $request):JsonResponse
 {
    try{
         $book =   $this->serializer->deserialize($request->getContent(),Book::class,'json');
         $this->entityManager->persist($book);
         $this->entityManager->flush();

             return $this->json([
                'message'=>'Livre créé avec succès','data'=>$book ],
                     status: 201,context:['groups'=>'book:read']);

                    
    }
    catch(\Exception $e)
    {
           return $this->json(['message'=>'Données invalides','error'=>$e->getMessage()],status:400);     
    }
 }

#[route('/books/{id}',name:'book_update',methods:['PATCH'])]
public function update(Request $request,Book $book,int $id):JsonResponse
{
   try{ 
   $this->serializer->deserialize($request->getContent(),Book::class,'json',['object_to_populate'=>$book]);
    $this->entityManager->flush();
    return $this->json(['message'=>"l \'objet avec un id $id a bien ete mis a jour",'data'=>$book],status:201,context:['book:read']);
   }
   catch(\Exception $e)
   {
      return $this->json(['message'=>'les donnees sont invalides','error'=>$e->getMessage()],status:400);
   }

}

#[route('/books/{id}' , name:'book_delete',requirements:['id'=>'\d'],methods:['DELETE'])]
public function delete(Book $book)
{
   try{
      $this->entityManager->remove($book);
      $this->entityManager->flush();
      return $this->json(['message'=>'les données on bien été supprimées'],status:201,context:['book:read']);
}
catch(\Exception $e)
{
  return $this->json(['message'=>'suppression non réaliser','error'=>$e->getMessage()],status:400,context:['book:read']);
}
          
}



}