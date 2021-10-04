<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SearchResource;
use App\Search;
use App\Email;
use Illuminate\Database\Eloquent\Builder;

class SearchStatisticsController extends Controller
{
    /**
     * Show the specified resource.
     *
     * @param \App\Search $search
     * @return \App\Http\Resources\SearchResource
     */
    public function show(Search $search): SearchResource
    {
        $email = Email::where('search_id',$search->id)->get();
       // dd($email);
        $search = Search::withCount([
            'emails',
            'urls',
            'urls as crawled_urls_count' => function (Builder $query) {
                $query->where('is_crawled', true);
            },
            'urls as not_crawled_urls_count' => function (Builder $query) {
                $query->where('is_crawled', false);
            }        
        ])->find($search->id);
        $search['all_emails'] =$email;
        return new SearchResource($search);
    }
}
