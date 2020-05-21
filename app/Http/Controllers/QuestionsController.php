<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Questions;

class QuestionsController extends Controller
{
    //
    public function getRoundQuestions( $type ) {
        // $columnSelectors = Questions::pluck('category')->
            // first();
        
        switch( $type ) {
            case 0:
            case 1:
            case 2:
            
                $columnSelectors = Questions::distinct('category')->
                //     pluck('category')->
                    where('round_type', $type)->
                    inRandomOrder()->
                    limit(6)->
                    get();
                break;
            default:
            
            return false;
            break;
        }

        $allColumns = collect();

        foreach( $columnSelectors as $column ) {
            $newColumn = Questions::where('category', $column->category)->
                where('round_type', $column->round_type)->
                inRandomOrder()->
                limit(5)->
                get();
            
            // dump( $newColumn );

            $allColumns->push( $newColumn );
        }

        // dd( $columnSelectors );

        // Set the column variable

        return response()->json( $allColumns );
        
    }
}
