# Stream Doctrine Example App

This example app shows you how you can use [GetStream.io](https://getstream.io/ "GetStream.io") to build a scalable social app, with posts, follows and likes.

The application is built using Doctrine and bootstrap and some available PSR-7 enabled components.

The project is based on the [stream-doctrine](https://github.com/GetStream/stream-doctrine) integration for [Stream](https://getstream.io/). There is also a lower level [PHP - Stream integration](https://github.com/getstream/stream-php) library which is suitable for all PHP applications.

You can sign up for a Stream account at [https://getstream.io/get_started](https://getstream.io/get_started).

If you're looking to self-host your feed solution we suggest the open source [Stream-Framework](https://github.com/tschellenbach/Stream-Framework), created by the Stream founders.

![](https://dvqg2dogggmn6.cloudfront.net/images/mood-home.png)

### Live demo

A live demo is run [on Heroku](http://doctrine-example.herokuapp.com/login/demo).

## Deploying the app

### Heroku

The best way to understand and try out this application is via Heroku. You can deploy the app, for free, simply by clicking the following button:

[![Deploy](https://www.herokucdn.com/deploy/button.png)](https://heroku.com/deploy)

### Local

If you prefer to run this locally then go ahead and use git to make a local clone of this repository.
Make sure to generate new API keys on [GetStream.io](https://getstream.io/).
Then paste your credentials in `.env` (copy the empty `.env.sample` file first) by filling in your `STREAM_APP_KEY` and `STREAM_APP_SECRET`.
Now you can get your database (SQLite) ready by running the migrate command (`composer migrate`)
One last thing before running the application: you'll need to generate a secret for the session encryption. Run `composer generate:key`, and you're done.

Run the web app locally by executing `php -S 127.0.0.1:8000 public/index.php` and open [http://localhost:8000/](http://localhost:8000/) in your browser.

### Copyright and License Information

Copyright (c) 2015-2017 Stream.io Inc, and individual contributors. All rights reserved.

See the file "LICENSE" for information on the history of this software, terms & conditions for usage, and a DISCLAIMER OF ALL WARRANTIES.
