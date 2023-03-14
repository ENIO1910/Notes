<?php

declare(strict_types=1);

namespace App\Controller;

class NoteController extends AbstractController
{
    private const PAGE_SIZE = 10;

    public function createAction()
    {
        if ($this->request->hasPost()) {
            $noteData = [
                'title' => $this->request->postParam('title'),
                'description' => $this->request->postParam('description')
            ];

            $this->noteModel->create($noteData);
            $this->redirect('/Notatki', ['before' => 'created']);
        }

        $this->view->render('create');
    }

    public function listAction(): void
    {
        $pageNumber = (int) $this->request->getParam('page', 1);
        $pageSize = (int) $this->request->getParam('pageSize', self::PAGE_SIZE);
        $sortBy = $this->request->getParam('sortby', 'title');
        $sortOrder = $this->request->getParam('sortOrder', 'desc');
        $phrase = $this->request->getParam('phrase');
        if (!in_array($pageSize, [1, 5, 10, 25]))
        {
            $pageSize = self::PAGE_SIZE;
        }

        if($phrase) {
            $notes = $this->noteModel->search($phrase ,$pageNumber, $pageSize, $sortBy, $sortOrder);
            $countNotes = $this->noteModel->searchCount($phrase);
        } else {
            $notes = $this->noteModel->list($pageNumber, $pageSize, $sortBy, $sortOrder);
            $countNotes = $this->noteModel->count();
        }

        $viewParams = [
            'page' => [
                'number' => $pageNumber,
                'size' => $pageSize,
                'pages' => (int) ceil($countNotes/$pageSize),
            ],
            'phrase' => $phrase,
            'sort' => ['by' => $sortBy, 'order' => $sortOrder
            ],
            'notes' => $notes,
            'before' => $this->request->getParam('before')
        ];

        $this->view->render('list', $viewParams);
    }

    public function showAction(): void
    {


        $note = $this->getNote();

        $this->view->render(
            'show',
            ['note' => $note]
        );
    }


    public function deleteAction(): void
    {

        if($this->request->isPost())
        {

            $id = (int) $this->request->postParam('id');
            $this->noteModel->delete($id);
            $this->redirect('/Notatki/', ['before' => 'deleted']);
        }


        $this->view->render('delete', ['note' => $this->getNote()]);
    }

    public function editAction()
    {
        if($this->request->isPost()) {
            $noteId = (int) $this->request->postParam('id');
            $noteData = [
                'title' => $this->request->postParam('title'),
                'description' => $this->request->postParam('description')
            ];

            $this->noteModel->update($noteId, $noteData);
            $this->redirect('/Notatki', ['before' => 'edited']);

            exit('tutaj');
        }

        $note = $this->getNote();

        $this->view->render(
            'edit',
            ['note' => $note]
        );
    }

    private function getNote(): array
    {
        $noteId = (int) $this->request->getParam('id');
        if(!$noteId) {
            $this->redirect('/Notatki', ['before' => 'missingNoteId']);
        }

        return $this->noteModel->get($noteId);

    }

}