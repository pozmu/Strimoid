<?php

class PollController extends BaseController
{

    public function addVote(Content $content)
    {
        $poll = $content->poll;

        // No double voting, sorry
        $hasVoted = in_array(Auth::user()->_id, array_column($poll['votes'], 'user_id'));

        if ($hasVoted)
        {
            return Redirect::route('content_comments', $content->_id)
                ->with('danger_msg', 'Oddałeś już głos w tej ankiecie.');
        }

        // Check if poll isn't closed already
        if (isset($poll['ends_at']) && Carbon::now()->gte(md_to_carbon($poll['ends_at'])))
        {
            return Redirect::route('content_comments', $content->_id)
                ->with('danger_msg', 'Ankieta została już zakończona.');
        }

        // Create validation rules for all questions
        $rules = array();

        foreach ($poll['questions'] as $questionId => $question)
        {
            $rules[$questionId] = ['array', 'min:'. $question['min_selections'], 'max:'. $question['max_selections']];

            if ($question['min_selections'])
            {
                $rules[$questionId][] = 'required';
            }
        }

        // Now validate replies
        $validator = Validator::make(Input::all(), $rules , [
            'required' => 'Odpowiedź na to pytanie jest wymagana',
            'min' => 'Zaznaczyłeś zbyt małą liczbę odpowiedzi',
            'max' => 'Zaznaczyłeś zbyt dużą liczbę odpowiedzi'
        ]);

        if ($validator->fails())
        {
            return Redirect::route('content_comments', $content->_id)
                ->withInput()
                ->withErrors($validator);
        }

        // And add vote object to poll
        $replies = array();

        foreach ($poll['questions'] as $questionId => $question)
        {
            $optionIds = (array) Input::get($questionId);

            foreach ($optionIds as $optionId)
            {
                if (!in_array($optionId, array_column($question['options'], '_id')))
                {
                    return Redirect::route('content_comments', $content->_id)
                        ->withInput()
                        ->with('danger_msg', 'Wygląda na to, że jedna z odpowiedzi została usunięta. Spróbuj jeszcze raz.');
                }
            }

            $replies[$questionId] = $optionIds;
        }

        foreach ($replies as $questionId => $optionIds)
        {
            if (!$optionIds)
            {
                continue;
            }

            foreach ($optionIds as $optionId)
            {
                Content::where('_id', $content->_id)
                    ->where('poll.questions.'. $questionId .'.options._id', $optionId)
                    ->increment('poll.questions.'. $questionId .'.options.$.votes', 1);
            }
        }

        $vote = ['created_at' => new MongoDate(), 'user_id' => Auth::user()->_id, 'replies' => $replies];

        $content->push('poll.votes', $vote);

        return Redirect::route('content_comments', $content->_id)
            ->with('success_msg', 'Twój głos został dodany.');
    }

}

