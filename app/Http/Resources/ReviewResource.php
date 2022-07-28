<?php

namespace App\Http\Resources;

use App\Models\Bath;
use App\Models\Journal;
use App\Models\Review;
use App\Models\Specialist;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource 
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $entity = [];
        if($this->type == 1) {
            $entity = Bath::where('id', $this->entity_id)->first();   
        }
        if($this->type == 2) {
            $entity = Specialist::where('id', $this->entity_id)->first();   
        }
        if($this->type == 3) {
            $entity = Journal::where('id', $this->entity_id)->first();   
        }
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'type' => $this->type,
            'entity_id' => $this->entity_id,
            'advantage' => $this->advantage,
            'flaw' => $this->flaw,
            'rating' => $this->rating,
            'moderate' => $this->moderate,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'images' => $this->images,
            'comment' => $this->commentcomment,
            'children_reviews' => $this->childrenReviews,
            'users' => $this->users,
            'entity' => $entity
        ];
    }
}
