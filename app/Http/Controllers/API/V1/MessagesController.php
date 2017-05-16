<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Message;
use App\Models\User;
use App\Transformers\V1\MessageTransformer;
use Illuminate\Http\Request;
use Validator;

class MessagesController extends BaseAPIController
{
    protected $resource_name = 'messages';

    /**
     * MessagesController constructor.
     * @param MessageTransformer $transformer
     */
    public function __construct(MessageTransformer $transformer)
    {
        parent::__construct();
        $this->transformer = $transformer;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if (auth()->user()->isAdmin()) {
            $term = request('term');
            $senderId = request('sender_user_id');
            $recipientId = request('recipient_user_id');

            if ($term) {
                // Indexed search
                $query = Message::search($term);
                if (is_numeric($senderId)) {
                    $query = $query->where('sender_user_id', (int) $senderId);
                }
                if (is_numeric($recipientId)) {
                    $query = $query->where('recipient_user_id', (int) $recipientId);
                }
            } else {
                //Non-indexed search
                $query = Message::make();
                if (is_numeric($senderId)) {
                    $query = $query->from(User::find($senderId));
                }
                if (is_numeric($recipientId)) {
                    $query = $query->to(User::find($recipientId));
                }
            }

            return $this->baseTransformBuilder($query, request('include'), new MessageTransformer, request('per_page'))->respond();
        }

        return $this->respondNotAuthorized('You are not authorized to access this resource.');
    }

    /**
     * @param Message $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Message $message)
    {
        if (auth()->user()->can('view', $message)) {
            return $this->baseTransformItem($message)->respond();
        }

        return $this->respondNotAuthorized("You do not have access to view the Message with id {$message->id}.");
    }
}
