<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Storage;

class ChatsController extends Controller
{
    //
    public function index() {

        // Possible Immidiate Improvements: 
        //
        // 1. Use/ Develop with Test Scripts - TDD.
        // 2. More/Add Error checking. (pretty scarce currently)
        // 3. Insert Json into database, then benefit from Laravel's db helpers for web and api
        // 4. Authorisation - perhaps with perhaps JWT (can use Laravel's tools)
        // 5. Investigate Laravels built in options for morphing/transforming data (maybe faster nder some conditions)
        // 6. Use Lumen with Laravel to setup Superfast Api and subesquently...
        // 7. convert to Microservice for better management and scalability.
        //


        // Using fixed file for now, maybe source can change.  
        // e.g.  to consume a publicAPI for chat updates
        $jsonFile = Storage::disk('local')->get('chatsource.json');

        // shorthand to home in to the chats specific data 
        $json = json_decode($jsonFile, true)['resources']['chats']['data'];

        // no longer required. File may be large, Release resources.
        unset($jsonFile);

        // make newArray a clean version by a map function
        $newArray = array_map(function($item) { 

            $clean_avatar = $item["avatar"]["sizes"][0]["uri"];
            $clean_date = $item["date"]["value"];

            //echo "<br><br>" . $clean_avatar . "<br>";

            //unset unused items as we map through 
            unset($item['date']);
            unset($item['photos']);
            unset($item['likes']);
            unset($item['avatar']);
            unset($item['in_reply_to_username']);
            unset($item['number_of_replies']);

            // add the extracted info
            $item['avatar'] = $clean_avatar;
            $item['date'] = $clean_date;

            // good debug point
            // var_dump($item);
            return $item;
           
        }, $json);

        // good debug point
        // var_dump($newArray);


        // added JSON_UNESCAPED_SLASHES to remove extra escape chars in URL
        return json_encode($newArray, JSON_UNESCAPED_SLASHES);
    }
}
