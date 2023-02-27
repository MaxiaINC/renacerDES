<?php

/*
 * This file is part of the FileGator package.
 *
 * (c) Milos Stojanovic <alcalbg@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE file
 */

namespace Filegator\Controllers;

use Filegator\Config\Config;
use Filegator\Kernel\Request;
use Filegator\Kernel\Response;
use Filegator\Services\Archiver\ArchiverInterface;
use Filegator\Services\Auth\AuthInterface;
use Filegator\Services\Session\SessionStorageInterface as Session;
use Filegator\Services\Storage\Filesystem;

class FileController
{
    const SESSION_CWD = 'current_path';

    protected $session;

    protected $auth;

    protected $config;

    protected $storage;

    protected $separator;

    public function __construct(Config $config, Session $session, AuthInterface $auth, Filesystem $storage)
    {
        $this->session = $session;
        $this->config = $config;
        $this->auth = $auth;

        $user = $this->auth->user() ?: $this->auth->getGuest();

        $this->storage = $storage;
        $this->storage->setPathPrefix($user->getHomeDir());

        $this->separator = $this->storage->getSeparator();
    }

    public function changeDirectory(Request $request, Response $response)
    {
        $path = $request->input('to', $this->separator);

        $this->session->set(self::SESSION_CWD, $path);
		
		$content 	= $this->storage->getDirectoryCollection($path);
		$contentenc = json_encode($content);
		$contenido 	= json_decode($contentenc, true);
		$salidas 	= array();
		
		//compruebo que los caracteres sean los permitidos		
		function validarnombre($nombre_archivo){
			$permitidos = "0123456789";
			$res = 0;
			for ($i=0; $i<strlen($nombre_archivo); $i++){
				if (strpos($permitidos, substr($nombre_archivo,$i,1))===false){
					$res = 1;
				}
			}
			return $res;
		}
		
		foreach ($contenido as $value => $obj) {
			if($value == 'files'){
				$arrfiles = json_decode(json_encode($obj), true);				
				foreach ($arrfiles as $i => $file) {
					$name = $file['name'];
					if(is_dir($_SERVER["DOCUMENT_ROOT"].'/soportedesnew/'.$file['path'])){
						$narchivo = validarnombre($file['name']);
						if($narchivo == 0 ||  $name == '.quarantine' || $name == '.tmb' || $name == '..' ||  $name == 'comentarios' ||  $name == 'compromisos' ){
							unset($arrfiles[$i]);
						}						
						//error_log('1: '.$file['path'].', '.$_SERVER["DOCUMENT_ROOT"].', '.$narchivo);
					}else{						
						if($name == '..' ){
							unset($arrfiles[$i]);
						}
						//error_log('2: '.$file['name']);
					}
				}
				$arrfiles = array_values($arrfiles);
			}else{
				$salidas[$value] = $obj;	
				//error_log('3: '.$salidas[$value]);				
			}
		}
		$salidas['files'] = $arrfiles;
		
		//error_log(json_encode($contenido));
		//error_log(json_encode($salidas));
		return $response->json($salidas);
        //return $response->json($this->storage->getDirectoryCollection($path));
    }
	
	public function getDirectory(Request $request, Response $response)
    {
        $path = $request->input('dir', $this->session->get(self::SESSION_CWD, $this->separator));

        $content 	= $this->storage->getDirectoryCollection($path);
		$contentenc = json_encode($content);
		$contenido 	= json_decode($contentenc, true);
		$salidas 	= array();
		
		//compruebo que los caracteres sean los permitidos		
		function validarnombre($nombre_archivo){
			$permitidos = "0123456789";
			$res = 0;
			for ($i=0; $i<strlen($nombre_archivo); $i++){
				if (strpos($permitidos, substr($nombre_archivo,$i,1))===false){
					$res = 1;
				}
			}
			return $res;
		}
		
		foreach ($contenido as $value => $obj) {
			if($value == 'files'){
				$arrfiles = json_decode(json_encode($obj), true);
				foreach ($arrfiles as $i => $file) {
					$name = $file['name'];
					if(is_dir($_SERVER["DOCUMENT_ROOT"].'/soportedesnew/'.$file['path'])){
						$narchivo = validarnombre($file['name']);
						if($narchivo == 0 || $name == '.quarantine' || $name == '.tmb' || $name == '..' ||  $name == 'comentarios' ||  $name == 'compromisos' ){
							unset($arrfiles[$i]);
						}
						//error_log('1: '.$file['path'].', '.$_SERVER["DOCUMENT_ROOT"].', '.$narchivo);
					}else{						
						if($name == '..' ){
							unset($arrfiles[$i]);
						}
						//error_log('2: '.$file['name']);
					}
				}
				$arrfiles = array_values($arrfiles);
			}else{
				$salidas[$value] = $obj;
			}
		}
		$salidas['files'] = $arrfiles;
		
		return $response->json($salidas);
    }

    public function createNew(Request $request, Response $response)
    {
        $type = $request->input('type', 'file');
        $name = $request->input('name');
        $path = $this->session->get(self::SESSION_CWD, $this->separator);

        if ($type == 'dir') {
            $this->storage->createDir($path, $request->input('name'));
        }
        if ($type == 'file') {
            $this->storage->createFile($path, $request->input('name'));
        }

        return $response->json('Done');
    }

    public function copyItems(Request $request, Response $response)
    {
        $items = $request->input('items', []);
        $destination = $request->input('destination', $this->separator);

        foreach ($items as $item) {
            if ($item->type == 'dir') {
                $this->storage->copyDir($item->path, $destination);
            }
            if ($item->type == 'file') {
                $this->storage->copyFile($item->path, $destination);
            }
        }

        return $response->json('Done');
    }

    public function moveItems(Request $request, Response $response)
    {
        $items = $request->input('items', []);
        $destination = $request->input('destination', $this->separator);

        foreach ($items as $item) {
            $full_destination = trim($destination, $this->separator)
                    .$this->separator
                    .ltrim($item->name, $this->separator);
            $this->storage->move($item->path, $full_destination);
        }

        return $response->json('Done');
    }

    public function zipItems(Request $request, Response $response, ArchiverInterface $archiver)
    {
        $items = $request->input('items', []);
        $destination = $request->input('destination', $this->separator);
        $name = $request->input('name', $this->config->get('frontend_config.default_archive_name'));

        $archiver->createArchive($this->storage);

        foreach ($items as $item) {
            if ($item->type == 'dir') {
                $archiver->addDirectoryFromStorage($item->path);
            }
            if ($item->type == 'file') {
                $archiver->addFileFromStorage($item->path);
            }
        }

        $archiver->storeArchive($destination, $name);

        return $response->json('Done');
    }

    public function unzipItem(Request $request, Response $response, ArchiverInterface $archiver)
    {
        $source = $request->input('item');
        $destination = $request->input('destination', $this->separator);

        $archiver->uncompress($source, $destination, $this->storage);

        return $response->json('Done');
    }

    public function renameItem(Request $request, Response $response)
    {
        $destination = $request->input('destination', $this->separator);
        $from = $request->input('from');
        $to = mb_ereg_replace("([\.]{2,})", '.', mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $request->input('to')));
        //$to = $request->input('to');
        
        $this->storage->rename($destination, $from, $to);

        return $response->json('Done');
    }

    public function deleteItems(Request $request, Response $response)
    {
        $items = $request->input('items', []);

        foreach ($items as $item) {
            if ($item->type == 'dir') {
                $this->storage->deleteDir($item->path);
            }
            if ($item->type == 'file') {
                $this->storage->deleteFile($item->path);
            }
        }

        return $response->json('Done');
    }

    public function saveContent(Request $request, Response $response)
    {
        $path = $request->input('dir', $this->session->get(self::SESSION_CWD, $this->separator));

        $name = $request->input('name');
        $content = $request->input('content');

        $stream = tmpfile();
        fwrite($stream, $content);
        rewind($stream);

        $this->storage->deleteFile($path.$this->separator.$name);
        $this->storage->store($path, $name, $stream);

        if (is_resource($stream)) {
            fclose($stream);
        }

        return $response->json('Done');
    }
}