<?php
namespace Congress\Lib\Model\Articles;

enum ArticleStatus: string
{
    case WaitingAssessor = '1_waiting';
    case WaitingEvaluation = '2_waiting_evaluation';
    case Approved = '3_approved';
    case Disapproved = '4_disapproved';

    public static function translate(ArticleStatus|string $enumValue) : string
    {
        $enumValue2 = $enumValue;
        if (is_string($enumValue))
            $enumValue2 = self::tryFrom($enumValue);

        if ($enumValue2 instanceof ArticleStatus)
            return match($enumValue2)
            {
                self::WaitingAssessor => 'Aguardando avaliador aceitar o artigo.',
                self::WaitingEvaluation => 'Aceito por avaliador. Aguardando avaliação.',
                self::Approved => 'Aprovado!',
                self::Disapproved => 'Reprovado!'
            };
        else 
            return '***';
    }
}