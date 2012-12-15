          __                    __      __                                 __
         /\ \                  /\ \    /\ \                               /\ \
         \_\ \     __      ____\ \ \___\ \ \____    ___      __     _ __  \_\ \    ____
         /'_` \  /'__`\   /',__\\ \  _ `\ \ '__`\  / __`\  /'__`\  /\`'__\/'_` \  /',__\
        /\ \L\ \/\ \L\.\_/\__, `\\ \ \ \ \ \ \L\ \/\ \L\ \/\ \L\.\_\ \ \//\ \L\ \/\__, `\
        \ \___,_\ \__/.\_\/\____/ \ \_\ \_\ \_,__/\ \____/\ \__/.\_\\ \_\\ \___,_\/\____/
         \/__,_ /\/__/\/_/\/___/   \/_/\/_/\/___/  \/___/  \/__/\/_/ \/_/ \/__,_ /\/___/

# Etsy Dashboards

Source code for Etsy's dashboards framework.

Take a look at the various `htdocs/example_*` files to get started.


INSTALLATION:
1. Create a new virtual host on your favourite web server, and point the
   DocumentRoot to the `htdocs/` directory.

2. Edit `htdocs/phplib/Dashboard.php`:
   i. Edit the varions server variables near the top, to point to your graphing
      servers.
   ii. You can edit the "TABS" section below that. Each of your dashboard pages
       should import one of the arrays, which will generate a set of navigation
       tabs at the top of that page.
       The index page also uses the tabs as a table of contents for you to start
       on. Check out the top of `htdocs/index.php` to see how that is organised.

3. Edit `htdocs/assets/js/dashboard.js`, and edit the server names there too.
   We apologies for making you put server names in two places. Still, it's
   better than three places!



HOW DASHBOARDS WORK:

There are a number of cool things you can do in your dashboards as you create
them.
The framework is designed such that you should not need to put any HTML in your
dashboard files to get your graphs to appear.

You can optionally set one variable, `$html_for_header`, which will be inserted
just above your graphs when the page is rendered.


BUILT IN GRAPH TYPES:
There are a number of handlers for graphs built in to the frame work.
Each type expects to find a global array named '$graphs', but the contents of
this array vary from handler to handler.
Here is what each of the current handlers expects:

 * Graphite
    An array of metrics to print on the graph. This can be a single metric name,
    or a list of metrics. Eg:
    ``array( 'stats.foo.bar', 'stats.foo.baz' );``

 * Ganglia
    An array with the following key/value pairs:
      source: The name of the ganglia cluster
      node: The name of the server
      datum: The name of the graph

 * Cacti
    Cacti does not require a complex array. The value of `metric` is the integer
    ID of the graph.

 * FITB
    FITB has no metric array, but expects the following variables for each graph
    object:
        host: The name of the network device
        portname: The name of the port on the device
        graphtype: One of 'bits', 'ucastpkts' or 'errors'
        title: The name you want for this graph

 * NewRelic:
    The NewRelic `metric` array for each graph requires two variables:
        time: One of the dashboard time frames, eg '1w', '2d', etc.
        url: The URL to the graph, as given when you make a graph public.
    The configuration for NewRelic requires multiple graph URLs for each graph
    you want displayed. Please see the `example_newrelic.php` file for more
    details.


DEPLOY LINES ON GRAPHS:

At Etsy we store a graphite metric each time we do a deploy. We store a unique
metric for each type of deploy.
For example, when we deploy our Web stack, we insert a '1' to
deploys.web.production. We do similar things for Search deploys, blog changes,
etc.

These lines get overlayed on all graphite graphs displayed in the dashboard.
If you do the same thing, you should add these stat names to the
`htdocs/phplib/DeployConstants.php` file. If you don't, you should delete the
entries in this file. The dashbaord framework will let you hide individual
deploy types on the dashboard pages.
