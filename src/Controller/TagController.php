<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\TypeTag;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;

class TagController extends BaseController {

    #[Route('/tags', name: 'all_tags')]
    public function getAll(): Response {
        $repository = $this->doctrine->getRepository(Tag::class);
        $data = $repository->findAll();

        usort($data, fn(Tag $a, Tag $b) =>
            strcmp($a->getName(), $b->getName())
        );

        //return new Response('ok', Response::HTTP_OK);
        $json = $this->serializer->serialize($data, 'json');
        return JsonResponse::fromJsonString($json, Response::HTTP_OK);
    }


    #[Route('/tags/types', name: 'get_types')]
    public function getTypes(): Response {
        $repository = $this->doctrine->getRepository(TypeTag::class);
        $data = $repository->findAll();

        $json = $this->serializer->serialize($data, 'json');
        return JsonResponse::fromJsonString($json, Response::HTTP_OK);
    }


    #[Route('/tags/match', name: 'match_tags')]
    public function getMatch(Request $request): Response {
        $em = $this->doctrine->getManager();
        $text = $request->query->get('q');

        $query = $em->createQuery('SELECT T FROM App\Entity\Tag T WHERE T.name LIKE :text');
        $query->setParameter('text', '%' . $text . '%');
        $result = $query->getResult();

        $json = $this->serializer->serialize($result, 'json');
        return JsonResponse::fromJsonString($json, Response::HTTP_OK);
    }


    #[Route('/tag/save', name: 'save_tag')]
    public function save(Request $request): Response {
        $em = $this->doctrine->getManager();
        $post = $request->toArray();
        $type = $em->find(TypeTag::class, $post['type']);
        $user = $em->find(User::class, 1);

        $tag = new Tag();
        $tag->setName($post['name']);
        $tag->setType($type);
        $tag->setDescription($post['description']);
        $tag->setCreatedBy($user);

        $em->persist($tag);
        $em->flush();

        $json = $this->serializer->serialize($tag, 'json');
        return JsonResponse::fromJsonString($json, Response::HTTP_OK);
    }
}
