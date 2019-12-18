<?php


namespace App\Controller;



use App\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;




/**
 * @Route("/post")
 */
class PostController extends AbstractController
{

    /**
     * @Route ("{page}", name="post_list", defaults={"page": 5}, requirements={"page"="\d+"})
     */
    public function list($page=1, Request $request)
    {
        $limit = $request-> get('limit', 10);
        $repository = $this->getDoctrine()->getRepository(Post::class);
        $items = $repository->findAll();

        return $this->json(
            [
                'page'=>$page,
                'limit'=>$limit,
                'data'=>array_map(function (Post $item){
                    return $this->generateUrl('post_by_slug', ['slug'=>$item->getSlug()]);
                }, $items)
            ]
        );
    }


    /**
     * @Route("/post/{id}", name="post_by_id", requirements={"id"="\d+"}, methods={"GET"})
     * @ParamConverter("post", class="App:Post")
     */
    public function post($post)
    {
        return $this->json($post);

    }


    /**
     * @Route("/post/{slug}", name="post_by_slug", methods={"GET"})
     * @ParamConverter("post", class="App:Post", options={"mapping": {"slug": "slug"}})
     */
    public function postBySlug(Post $post)
    {
        return $this->json($post);
    }


    /**
     * @Route("/add", name="post_add", methods={"POST"})
     */
    public function add(Request $request)
    {
        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');

        $post = $serializer->deserialize($request->getContent(), Post::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();

        return $this->json($post);
    }

    /**
     * @Route("/post/{id}", name="post_delete", methods={"DELETE"})
     */
    public function delete(Post $post)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }





}