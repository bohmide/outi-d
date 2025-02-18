<?php

namespace App\Controller\back;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Quiz;
use App\Entity\Question;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\QuestionType;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;


final class QuestionBackController extends AbstractController
{
    #[Route('/quiz/{id}/questions', name: 'admin_quiz_question')]
    public function showQuestion(Quiz $quiz): Response
    {
        $questions = $quiz->getQuestion();

        return $this->render('back/questions/showQuestion.html.twig', [
            'quiz' => $quiz,
            'questions' => $questions,
        ]);
    }
    #[Route('/quiz/{id}/questions/new', name: 'admin_questions_new')]
    public function quizQuestions(int $id, Request $request, ManagerRegistry $m)
    {
        $em = $m->getManager();
        $quiz = $m->getRepository(Quiz::class)->find($id);
        if (!$quiz) {
            throw $this->createNotFoundException('Quiz not found');
        }

        // Fetch all the questions associated with the quiz
        $questions = $quiz->getQuestion();

        // Create a new question form
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $question->setQuiz($quiz);
            $em->persist($question);
            $em->flush();
            $this->addFlash('success', 'Question added successfully!');
            return $this->redirectToRoute('admin_quiz_question', ['id' => $quiz->getId()]);
        }

        return $this->render('back/questions/newQuestion.html.twig', [
            'quiz' => $quiz,
            'questions' => $questions,
            'form' => $form,
        ]);
    }

    #[Route('/quiz/{quiz_id}/question/{id}/edit', name: 'admin_quiz_question_edit')]
    public function editQuestion(#[MapEntity(mapping: ['quiz_id' => 'id'])] Quiz $quiz, 
    #[MapEntity(mapping: ['id' => 'id'])] Question $question , Request $request, ManagerRegistry $ma): Response
    {
            // Récupérer le quiz et la question en fonction de quiz_id et id

        $em = $ma->getManager();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $question->setQuiz($quiz); // Réaffecter le quiz à la question
            $em->flush();
        
            $this->addFlash('success', 'Question modifiée avec succès');
            return $this->redirectToRoute('admin_quiz_question', ['id' => $quiz->getId()]);
        }
        
        return $this->render('back/questions/editQuestion.html.twig', [
            'quiz' => $quiz,
            'form' => $form,
            'question' => $question,
        ]);
}
     #[Route('/quiz/{quiz_id}/question/{id}/delete', name: 'admin_quiz_question_delete')]

    public function deleteQuestion(Question $question, $quiz_id, ManagerRegistry $ma): Response
  {
    $em = $ma->getManager();
    $em->remove($question);
    $em->flush();

    $this->addFlash('success', 'Question supprimée avec succès.');  
    return $this->redirectToRoute('admin_quiz_question', ['id' => $quiz_id]);
  }

}
