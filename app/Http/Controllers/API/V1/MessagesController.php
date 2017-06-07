<?php

namespace App\Http\Controllers\API\V1;

use App\Lib\Validation\StrictValidator;
use App\Models\Message;
use App\Models\User;
use App\Transformers\V1\MessageTransformer;
use Illuminate\Http\Request;
use ResponseCode;

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
        $filterUnread = 'unread' === request('filter');
        $currentUserId = currentUser()->id;
        $recipientId = null;
        $senderId = request('sender_user_id');
        $term = request('term');

        if (currentUser()->isAdmin() && request('recipient_user_id')) {
            $recipientId = request('recipient_user_id');
        }

        $query = Message::make();

        if ($term) {
            $query = $query->whereIn('id', Message::search($term)->get()->pluck('id'));
        }

        if ($filterUnread) {
            $query = $query->unread();
        }

        if (is_numeric($senderId)) {
            $query = $query->from(User::find($senderId));
        }

        if (is_numeric($recipientId)) {
            $query = $query->to(User::find($recipientId));
        } else {
            $query = $query->senderOrRecipient(currentUser());
        }

        return $this->baseTransformBuilder($query, request('include'), new MessageTransformer, request('per_page'))->respond();
    }

    /**
     * @param Request     $request
     * @param Message     $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function read(Request $request, Message $message)
    {
        if (currentUser()->can('markAsRead', $message)) {
            $message->setReadAt()->save();
            return $this->baseTransformItem($message)->respond();
        }

        return $this->respondNotAuthorized("You do not have access to update the Message with ID #{$message->id}.");
    }

    /**
     * @param Request     $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function new(Request $request)
    {
        $validator = StrictValidator::check($request->all(), [
            'recipient_user_id' => 'required|exists:users,id',
            'message' => 'required',
            'subject' => 'required',
        ]);

        $message = new Message($request->all());

        if (currentUser()->can('create', $message)) {
            $message->is_admin = currentUser()->isAdmin();
            $message->sender_user_id = currentUser()->id;
            $message->save();

            return $this->baseTransformItem($message)->respond(ResponseCode::HTTP_CREATED);
        }

        return $this->respondNotAuthorized("You do not have access to create a new Message.");
    }

    /**
     * @param Message $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Message $message)
    {
        if (currentUser()->can('view', $message)) {
            return $this->baseTransformItem($message)->respond();
        }

        return $this->respondNotAuthorized("You do not have access to view the Message with ID #{$message->id}.");
    }

    /**
     * @param Message $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Message $message)
    {
        if (currentUser()->can('delete', $message)) {
            $message->delete();
            return $this->baseTransformItem($message)->addMeta(['deleted' => true])->respond(ResponseCode::HTTP_NO_CONTENT);
        }

        return $this->respondNotAuthorized("You do not have access to delete the Message with ID #{$message->id}.");
    }
}
