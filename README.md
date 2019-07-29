# League of Stats

This application utilizes Riot Games' League of Legends API in order to fetch, and subsequently serve, general data of the specified Summoner.

## Installation

### API Key

Go to ``https://developer.riotgames.com/``.

Sign in with your League of Legends account, if you have one.

If you don't have one, register, and then sign in.

Request a new API key if necessary, otherwise copy your existing API key.

### Setting Up The App Environment

Within this app's file directories, navigate to the ``config`` folder.

Make a new file named ``environment.ini`` inside of the ``config`` folder.

Once you've opened the ``environment.ini`` file, paste in the following snippet.

```
[api_key]
api_key = "<YOUR API KEY GOES HERE>"
```

Replace ``<YOUR API KEY GOES HERE>`` with your Riot Games API Key.

### Using XAMPP

If you're using XAMPP ( or a similar local system hosting application service ), follow the directions listed here.

1. Clone the repository into your ``xampp/htdocs`` directory.

2. Open XAMPP and start the Apache and MySQL services.

3. Within your browser, go to ``localhost``.

4. Enter in a Summoner Name and a region ( defaults to NA ), and press the button to fetch that Summoner's data.


## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
[MIT License](https://choosealicense.com/licenses/mit/)

Copyright (c) 2019 Jesse Mack

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
