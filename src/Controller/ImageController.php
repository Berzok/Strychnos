<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Image;
use App\Entity\Tag;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use GdImage;
use Imagick;
use ImagickException;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends BaseController {

    #[Route('/images', name: 'images')]
    public function findAll(Request $request): Response {
        $tags = $request->get('tags') ?: null;
        $limit = $request->get('limit') ?: null;
        $offset = $request->get('offset') ?: null;
        $repository = $this->doctrine->getRepository(Image::class);

        if(!is_null($tags)){
            $tags = explode('+', $tags);
            foreach($tags as &$t){
                $t = $this->doctrine->getRepository(Tag::class)->findOneBy([ 'name' => $t ]);
            }
            $data = $repository->findByTags($tags[0]);

        } else{
            $data = $repository->findBy([], ['id' => 'DESC'], $limit, $offset);
        }

        $json = $this->serializer->serialize($data, 'json');
        //return new Response('ok', Response::HTTP_OK);
        return JsonResponse::fromJsonString($json, Response::HTTP_OK);
    }


    #[Route('/image/get/{id}', name: 'get_image', methods: ['GET'])]
    public function findOne(int $id): Response {
        $repository = $this->doctrine->getRepository(Image::class);
        $data = $repository->find($id);

        $json = $this->serializer->serialize($data, 'json');

        return JsonResponse::fromJsonString($json, Response::HTTP_OK);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    #[Route('/images/count', name: 'get_count')]
    public function getCount(): Response {
        $repository = $this->doctrine->getRepository(Image::class);
        $data = $repository
            ->createQueryBuilder('i')
            ->select('count(i.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return new Response($data, Response::HTTP_OK);
    }

    #[Route('/image/file/{id}', name: 'get_raw')]
    public function serveImage(int $id): mixed {
        $image = $this->doctrine->getRepository(Image::class)->find($id);
        $file = 'uploads/' . $image->getFilename();

        return new BinaryFileResponse($file);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/image/create', name: 'create_image')]
    public function create(Request $request): Response {
        $em = $this->doctrine->getManager();
        $artist_repository = $this->doctrine->getRepository(Artist::class);

        $params = $request->toArray();
        $filename = $params['filename'];
        $extension = $params['extension'];
        $url = $params['url'];
        $source = $params['source'];
        $user = $params['artist'];

        $artist = $artist_repository->findOneBy(['idAccount' => $user['id']]);
        if (!isset($artist)) {
            $artist = new Artist();
            $artist->setAccount($user['account']);
            $artist->setDescription('');
            $artist->setIdAccount($user['id']);
            $artist->setName($user['name']);
            $artist->setUrl('https://www.pixiv.net/en/users/' . $user['id']);
        }

        $image = new Image();
        $image->setArtist($artist);
        $image->setUrl($url);
        $image->setFilename($filename . $extension);
        $image->setSource($source);

        $em->persist($image);
        $em->flush();

        return new Response('ok', Response::HTTP_OK);
    }


    #[Route('/image/update', name: 'update_image')]
    public function save(Request $request): Response{
        $em = $this->doctrine->getManager();
        $tag_repository = $this->doctrine->getRepository(Tag::class);

        $params = $request->toArray();
        $id = $params['id'];
        $tags = $params['tags'];

        $image = $em->find(Image::class, $id);
        $image->setTags(new ArrayCollection());

        foreach($tags as $key => $value){
            //Tag not in current tags, so we add it
            $t = $tag_repository->find($value['id']);
            if(!$image->getTags()->contains($value)){
                $image->addTag($t);
            }
        }

        $em->persist($image);
        $em->flush();

        $json = $this->serializer->serialize($tags, 'json');
        return new Response($json, Response::HTTP_OK);
    }

}
