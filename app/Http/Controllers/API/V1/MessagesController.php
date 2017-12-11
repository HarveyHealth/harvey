<?php

namespace App\Http\Controllers\API\V1;

use App\Lib\Validation\StrictValidator;
use App\Models\Message;
use App\Models\User;
use App\Transformers\V1\MessageTransformer;
use Illuminate\Http\Request;
use ResponseCode;
use Carbon\Carbon;

class MessagesController extends BaseAPIController
{
    protected $resource_name = 'message';

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
    public function getAll()
    {
        $filterUnread = 'unread' === request('filter');
        $currentUserId = currentUser()->id;
        $recipientId = null;
        $senderId = request('sender_user_id');
        $term = request('term');

        if (currentUser()->isAdmin() && request('recipient_user_id')) {
            $recipientId = request('recipient_user_id');
        }

        $builder = Message::make();

        if ($term) {
            $builder = $builder->whereIn('id', Message::search($term)->get()->pluck('id'));
        }

        if ($filterUnread) {
            $builder = $builder->unread();
        }

        if (is_numeric($senderId)) {
            $builder = $builder->from(User::find($senderId));
        }

        if (is_numeric($recipientId)) {
            $builder = $builder->to(User::find($recipientId));
        } else {
            $builder = $builder->senderOrRecipient(currentUser());
        }

        // filter by most recent messages

        // get the date if the latest unread message
        $query = clone $builder;
        $unread_date = $query->orderBy('created_at', 'desc')->unread()->limit(1)->value('created_at');
        $date_from = (!empty($unread_date))? \Carbon::parse($unread_date):null;

        if(empty($date_from)){
            // get the date of the latest message
            $query = clone $builder;
            $latest_date = $query->orderBy('created_at', 'desc')->limit(1)->value('created_at');
            $date_from = (!empty($latest_date))? \Carbon::parse($latest_date):null;
        }

        if (!empty($date_from)){
            $builder->createdAfter($date_from->subDays(10));
        }

        return $this->baseTransformBuilder($builder->with('sender')->with('recipient'), request('include'), new MessageTransformer, request('per_page'))->respond();
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
        StrictValidator::check($request->all(), [
            'recipient_user_id' => 'required|exists:users,id',
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
    public function getOne(Message $message)
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
        if (currentUser()->cant('delete', $message)) {
            return $this->respondNotAuthorized("You do not have access to delete the Message with ID #{$message->id}.");
        }

        if (!$message->delete()) {
            return $this->baseTransformItem($message)->respond(ResponseCode::HTTP_CONFLICT);
        }

        return response()->json([], ResponseCode::HTTP_NO_CONTENT);
    }
}
