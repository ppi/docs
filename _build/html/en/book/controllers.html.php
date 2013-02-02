


    <div class="container" id="docspage-content">
        
        <div id="action-content" data-prevpage="Routing" data-prevlink="routing.html.php" data-nextpage="Templating" data-nextlink="templating.html.php"
        >
        
            <div class="content-box docs-page">
                
  <div class="section" id="controllers">
<span id="index-0"></span><h1>Controllers<a class="headerlink" href="#controllers" title="Permalink to this headline">¶</a></h1>
<p>So what is a controller? A controller is just a PHP class, like any other that you&#8217;ve created before, but the intention of it, is to have a bunch of methods on it called actions. The idea is: each route in your system will execute an action method. Examples of action methods would be your homepage or blog post page. The job of a controller is to perform a bunch of code and respond with some HTTP content to be sent back to the browser. The response could be a HTML page, a JSON array, XML document or to redirect somewhere. Controllers in PPI are ideal for making anything from web services, to web applications, to just simple html-driven websites.</p>
<p>Lets quote something we said in the last chapter&#8217;s introduction section</p>
<div class="section" id="defaults">
<h2>Defaults<a class="headerlink" href="#defaults" title="Permalink to this headline">¶</a></h2>
<p>This is the important part, The syntax is <tt class="docutils literal"><span class="pre">Module:Controller:action</span></tt>. So if you specify Application:Blog:show then this will execute the following class path: <tt class="docutils literal"><span class="pre">/modules/Application/Controller/Blog-&gt;showAction()</span></tt>. Notice how the method has a suffix of Action, this is so you can have lots of methods on your controller but only the ones ending in <tt class="docutils literal"><span class="pre">Action()</span></tt> will be executable from a route.</p>
</div>
<div class="section" id="example-controller">
<h2>Example controller<a class="headerlink" href="#example-controller" title="Permalink to this headline">¶</a></h2>
<p>Review the following route that we&#8217;ll be matching.</p>
<div class="highlight-yaml"><div class="highlight"><pre><span class="l-Scalar-Plain">Blog_Show</span><span class="p-Indicator">:</span>
    <span class="l-Scalar-Plain">pattern</span><span class="p-Indicator">:</span> <span class="l-Scalar-Plain">/blog/{id}</span>
    <span class="l-Scalar-Plain">defaults</span><span class="p-Indicator">:</span> <span class="p-Indicator">{</span> <span class="nv">_controller</span><span class="p-Indicator">:</span> <span class="s">&quot;Application:Blog:show&quot;</span><span class="p-Indicator">}</span>
</pre></div>
</div>
<p>So lets presume the route is <tt class="docutils literal"><span class="pre">/blog/show/{id}</span></tt>, and look at what your controller would look like. Here is an example blog controller, based on some of the routes provided above.</p>
<div class="highlight-php"><div class="highlight"><pre><span class="o">&lt;?</span><span class="nx">php</span>
<span class="k">namespace</span> <span class="nx">Application\Controller</span><span class="p">;</span>

<span class="k">use</span> <span class="nx">Application\Controller\Shared</span> <span class="k">as</span> <span class="nx">BaseController</span><span class="p">;</span>

<span class="k">class</span> <span class="nc">Blog</span> <span class="k">extends</span> <span class="nx">BaseController</span> <span class="p">{</span>

    <span class="k">public</span> <span class="k">function</span> <span class="nf">showAction</span><span class="p">()</span> <span class="p">{</span>

        <span class="nv">$blogID</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getRouteParam</span><span class="p">(</span><span class="s1">&#39;id&#39;</span><span class="p">);</span>

        <span class="nv">$bs</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getBlogStorage</span><span class="p">();</span>

        <span class="k">if</span><span class="p">(</span><span class="o">!</span><span class="nv">$bs</span><span class="o">-&gt;</span><span class="na">existsByID</span><span class="p">(</span><span class="nv">$blogID</span><span class="p">))</span> <span class="p">{</span>
            <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">setFlash</span><span class="p">(</span><span class="s1">&#39;error&#39;</span><span class="p">,</span> <span class="s1">&#39;Invalid Blog ID&#39;</span><span class="p">);</span>
            <span class="k">return</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">redirectToRoute</span><span class="p">(</span><span class="s1">&#39;Blog_Index&#39;</span><span class="p">);</span>
        <span class="p">}</span>

        <span class="c1">// Get the blog post for this ID</span>
        <span class="nv">$blogPost</span> <span class="o">=</span> <span class="nv">$bs</span><span class="o">-&gt;</span><span class="na">getByID</span><span class="p">(</span><span class="nv">$blogID</span><span class="p">);</span>

        <span class="c1">// Render our main blog page, passing in our $blogPost article to be rendered</span>
        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render</span><span class="p">(</span><span class="s1">&#39;Application:blog:show.html.php&#39;</span><span class="p">,</span> <span class="nb">compact</span><span class="p">(</span><span class="s1">&#39;blogPost&#39;</span><span class="p">));</span>
    <span class="p">}</span>

<span class="p">}</span>
</pre></div>
</div>
</div>
<div class="section" id="generating-urls-using-routes">
<h2>Generating urls using routes<a class="headerlink" href="#generating-urls-using-routes" title="Permalink to this headline">¶</a></h2>
<p>Here we are still executing the same route, but making up some urls using route names</p>
<div class="highlight-php"><div class="highlight"><pre><span class="o">&lt;?</span><span class="nx">php</span>
<span class="k">namespace</span> <span class="nx">Application\Controller</span><span class="p">;</span>

<span class="k">use</span> <span class="nx">Application\Controller\Shared</span> <span class="k">as</span> <span class="nx">BaseController</span><span class="p">;</span>

<span class="k">class</span> <span class="nc">Blog</span> <span class="k">extends</span> <span class="nx">BaseController</span> <span class="p">{</span>

    <span class="k">public</span> <span class="k">function</span> <span class="nf">showAction</span><span class="p">()</span> <span class="p">{</span>

        <span class="nv">$blogID</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getRouteParam</span><span class="p">(</span><span class="s1">&#39;id&#39;</span><span class="p">);</span>

        <span class="c1">// pattern: /about</span>
        <span class="nv">$aboutUrl</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">generateUrl</span><span class="p">(</span><span class="s1">&#39;About_Page&#39;</span><span class="p">);</span>

        <span class="c1">// pattern: /blog/show/{id}</span>
        <span class="nv">$blogPostUrl</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">generateUrl</span><span class="p">(</span><span class="s1">&#39;Blog_Post&#39;</span><span class="p">,</span> <span class="k">array</span><span class="p">(</span><span class="s1">&#39;id&#39;</span> <span class="o">=&gt;</span> <span class="nv">$blogID</span><span class="p">);</span>

    <span class="p">}</span>
<span class="p">}</span>
</pre></div>
</div>
</div>
<div class="section" id="redirecting-to-routes">
<h2>Redirecting to routes<a class="headerlink" href="#redirecting-to-routes" title="Permalink to this headline">¶</a></h2>
<p>An extremely handy way to send your users around your application is redirect them to a specific route.</p>
<div class="highlight-php"><div class="highlight"><pre><span class="o">&lt;?</span><span class="nx">php</span>
<span class="k">namespace</span> <span class="nx">Application\Controller</span><span class="p">;</span>

<span class="k">use</span> <span class="nx">Application\Controller\Shared</span> <span class="k">as</span> <span class="nx">BaseController</span><span class="p">;</span>

<span class="k">class</span> <span class="nc">Blog</span> <span class="k">extends</span> <span class="nx">BaseController</span> <span class="p">{</span>

    <span class="k">public</span> <span class="k">function</span> <span class="nf">showAction</span><span class="p">()</span> <span class="p">{</span>

        <span class="c1">// Send user to /login, if they are not logged in</span>
        <span class="k">if</span><span class="p">(</span><span class="o">!</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">isLoggedIn</span><span class="p">())</span> <span class="p">{</span>
            <span class="k">return</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">redirectToRoute</span><span class="p">(</span><span class="s1">&#39;User_Login&#39;</span><span class="p">);</span>
        <span class="p">}</span>

        <span class="c1">// go to /user/profile/{username}</span>
        <span class="k">return</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">redirectToRoute</span><span class="p">(</span><span class="s1">&#39;User_Profile&#39;</span><span class="p">,</span> <span class="k">array</span><span class="p">(</span><span class="s1">&#39;username&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;ppi_user&#39;</span><span class="p">));</span>

    <span class="p">}</span>
<span class="p">}</span>
</pre></div>
</div>
</div>
<div class="section" id="working-with-post-values">
<h2>Working with <tt class="docutils literal"><span class="pre">POST</span></tt> values<a class="headerlink" href="#working-with-post-values" title="Permalink to this headline">¶</a></h2>
<div class="highlight-php"><div class="highlight"><pre><span class="o">&lt;?</span><span class="nx">php</span>
<span class="k">namespace</span> <span class="nx">Application\Controller</span><span class="p">;</span>

<span class="k">use</span> <span class="nx">Application\Controller\Shared</span> <span class="k">as</span> <span class="nx">BaseController</span><span class="p">;</span>

<span class="k">class</span> <span class="nc">Blog</span> <span class="k">extends</span> <span class="nx">BaseController</span> <span class="p">{</span>

    <span class="k">public</span> <span class="k">function</span> <span class="nf">postAction</span><span class="p">()</span> <span class="p">{</span>

        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getPost</span><span class="p">()</span><span class="o">-&gt;</span><span class="na">set</span><span class="p">(</span><span class="s1">&#39;myKey&#39;</span><span class="p">,</span> <span class="s1">&#39;myValue&#39;</span><span class="p">);</span>

        <span class="nb">var_dump</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getPost</span><span class="p">()</span><span class="o">-&gt;</span><span class="na">get</span><span class="p">(</span><span class="s1">&#39;myKey&#39;</span><span class="p">));</span> <span class="c1">// string(&#39;myValue&#39;)</span>

        <span class="nb">var_dump</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getPost</span><span class="p">()</span><span class="o">-&gt;</span><span class="na">has</span><span class="p">(</span><span class="s1">&#39;myKey&#39;</span><span class="p">));</span> <span class="c1">// bool(true)</span>

        <span class="nb">var_dump</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getPost</span><span class="p">()</span><span class="o">-&gt;</span><span class="na">remove</span><span class="p">(</span><span class="s1">&#39;myKey&#39;</span><span class="p">));</span>
        <span class="nb">var_dump</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getPost</span><span class="p">()</span><span class="o">-&gt;</span><span class="na">has</span><span class="p">(</span><span class="s1">&#39;myKey&#39;</span><span class="p">));</span> <span class="c1">// bool(false)</span>

        <span class="c1">// To get all the post values</span>
        <span class="nv">$postValues</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">post</span><span class="p">();</span>

    <span class="p">}</span>
<span class="p">}</span>
</pre></div>
</div>
</div>
<div class="section" id="working-with-querystring-parameters">
<h2>Working with QueryString parameters<a class="headerlink" href="#working-with-querystring-parameters" title="Permalink to this headline">¶</a></h2>
<div class="highlight-php"><div class="highlight"><pre><span class="o">&lt;?</span><span class="nx">php</span>
<span class="k">namespace</span> <span class="nx">Application\Controller</span><span class="p">;</span>

<span class="k">use</span> <span class="nx">Application\Controller\Shared</span> <span class="k">as</span> <span class="nx">BaseController</span><span class="p">;</span>

<span class="k">class</span> <span class="nc">Blog</span> <span class="k">extends</span> <span class="nx">BaseController</span> <span class="p">{</span>


    <span class="c1">// The URL is /blog/?action=show&amp;id=453221</span>
    <span class="k">public</span> <span class="k">function</span> <span class="nf">queryStringAction</span><span class="p">()</span> <span class="p">{</span>

        <span class="nb">var_dump</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getQueryString</span><span class="p">()</span><span class="o">-&gt;</span><span class="na">get</span><span class="p">(</span><span class="s1">&#39;action&#39;</span><span class="p">));</span> <span class="c1">// string(&#39;show&#39;)</span>
        <span class="nb">var_dump</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getQueryString</span><span class="p">()</span><span class="o">-&gt;</span><span class="na">has</span><span class="p">(</span><span class="s1">&#39;id&#39;</span><span class="p">));</span> <span class="c1">// bool(true)</span>

        <span class="c1">// Get all the query string values</span>
        <span class="nv">$allValues</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">queryString</span><span class="p">();</span>

    <span class="p">}</span>
<span class="p">}</span>
</pre></div>
</div>
</div>
<div class="section" id="working-with-server-variables">
<h2>Working with server variables<a class="headerlink" href="#working-with-server-variables" title="Permalink to this headline">¶</a></h2>
<div class="highlight-php"><div class="highlight"><pre><span class="o">&lt;?</span><span class="nx">php</span>
<span class="k">namespace</span> <span class="nx">Application\Controller</span><span class="p">;</span>

<span class="k">use</span> <span class="nx">Application\Controller\Shared</span> <span class="k">as</span> <span class="nx">BaseController</span><span class="p">;</span>

<span class="k">class</span> <span class="nc">Blog</span> <span class="k">extends</span> <span class="nx">BaseController</span> <span class="p">{</span>

    <span class="k">public</span> <span class="k">function</span> <span class="nf">serverAction</span><span class="p">()</span> <span class="p">{</span>

        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getServer</span><span class="p">()</span><span class="o">-&gt;</span><span class="na">set</span><span class="p">(</span><span class="s1">&#39;myKey&#39;</span><span class="p">,</span> <span class="s1">&#39;myValue&#39;</span><span class="p">);</span>

        <span class="nb">var_dump</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getServer</span><span class="p">()</span><span class="o">-&gt;</span><span class="na">get</span><span class="p">(</span><span class="s1">&#39;myKey&#39;</span><span class="p">));</span> <span class="c1">// string(&#39;myValue&#39;)</span>

        <span class="nb">var_dump</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getServer</span><span class="p">()</span><span class="o">-&gt;</span><span class="na">has</span><span class="p">(</span><span class="s1">&#39;myKey&#39;</span><span class="p">));</span> <span class="c1">// bool(true)</span>

        <span class="nb">var_dump</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getServer</span><span class="p">()</span><span class="o">-&gt;</span><span class="na">remove</span><span class="p">(</span><span class="s1">&#39;myKey&#39;</span><span class="p">));</span>
        <span class="nb">var_dump</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getServer</span><span class="p">()</span><span class="o">-&gt;</span><span class="na">has</span><span class="p">(</span><span class="s1">&#39;myKey&#39;</span><span class="p">));</span> <span class="c1">// bool(false)</span>

        <span class="c1">// Get all server values</span>
        <span class="nv">$allServerValues</span> <span class="o">=</span>  <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">server</span><span class="p">();</span>

    <span class="p">}</span>
<span class="p">}</span>
</pre></div>
</div>
</div>
<div class="section" id="working-with-cookies">
<h2>Working with cookies<a class="headerlink" href="#working-with-cookies" title="Permalink to this headline">¶</a></h2>
<div class="highlight-php"><div class="highlight"><pre><span class="o">&lt;?</span><span class="nx">php</span>
<span class="k">namespace</span> <span class="nx">Application\Controller</span><span class="p">;</span>

<span class="k">use</span> <span class="nx">Application\Controller\Shared</span> <span class="k">as</span> <span class="nx">BaseController</span><span class="p">;</span>

<span class="k">class</span> <span class="nc">Blog</span> <span class="k">extends</span> <span class="nx">BaseController</span> <span class="p">{</span>

    <span class="k">public</span> <span class="k">function</span> <span class="nf">cookieAction</span><span class="p">()</span> <span class="p">{</span>

        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getCookie</span><span class="p">()</span><span class="o">-&gt;</span><span class="na">set</span><span class="p">(</span><span class="s1">&#39;myKey&#39;</span><span class="p">,</span> <span class="s1">&#39;myValue&#39;</span><span class="p">);</span>

        <span class="nb">var_dump</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getCookie</span><span class="p">()</span><span class="o">-&gt;</span><span class="na">get</span><span class="p">(</span><span class="s1">&#39;myKey&#39;</span><span class="p">));</span> <span class="c1">// string(&#39;myValue&#39;)</span>

        <span class="nb">var_dump</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getCookie</span><span class="p">()</span><span class="o">-&gt;</span><span class="na">has</span><span class="p">(</span><span class="s1">&#39;myKey&#39;</span><span class="p">));</span> <span class="c1">// bool(true)</span>

        <span class="nb">var_dump</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getCookie</span><span class="p">()</span><span class="o">-&gt;</span><span class="na">remove</span><span class="p">(</span><span class="s1">&#39;myKey&#39;</span><span class="p">));</span>
        <span class="nb">var_dump</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getCookie</span><span class="p">()</span><span class="o">-&gt;</span><span class="na">has</span><span class="p">(</span><span class="s1">&#39;myKey&#39;</span><span class="p">));</span> <span class="c1">// bool(false)</span>

        <span class="c1">// Get all the cookies</span>
        <span class="nv">$cookies</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">cookies</span><span class="p">();</span>

    <span class="p">}</span>
<span class="p">}</span>
</pre></div>
</div>
</div>
<div class="section" id="working-with-session-values">
<h2>Working with session values<a class="headerlink" href="#working-with-session-values" title="Permalink to this headline">¶</a></h2>
<div class="highlight-php"><div class="highlight"><pre><span class="o">&lt;?</span><span class="nx">php</span>
<span class="k">namespace</span> <span class="nx">Application\Controller</span><span class="p">;</span>

<span class="k">use</span> <span class="nx">Application\Controller\Shared</span> <span class="k">as</span> <span class="nx">BaseController</span><span class="p">;</span>

<span class="k">class</span> <span class="nc">Blog</span> <span class="k">extends</span> <span class="nx">BaseController</span> <span class="p">{</span>

    <span class="k">public</span> <span class="k">function</span> <span class="nf">sessionAction</span><span class="p">()</span> <span class="p">{</span>

        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getSession</span><span class="p">()</span><span class="o">-&gt;</span><span class="na">set</span><span class="p">(</span><span class="s1">&#39;myKey&#39;</span><span class="p">,</span> <span class="s1">&#39;myValue&#39;</span><span class="p">);</span>

        <span class="nb">var_dump</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getSession</span><span class="p">()</span><span class="o">-&gt;</span><span class="na">get</span><span class="p">(</span><span class="s1">&#39;myKey&#39;</span><span class="p">));</span> <span class="c1">// string(&#39;myValue&#39;)</span>

        <span class="nb">var_dump</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getSession</span><span class="p">()</span><span class="o">-&gt;</span><span class="na">has</span><span class="p">(</span><span class="s1">&#39;myKey&#39;</span><span class="p">));</span> <span class="c1">// bool(true)</span>

        <span class="nb">var_dump</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getSession</span><span class="p">()</span><span class="o">-&gt;</span><span class="na">remove</span><span class="p">(</span><span class="s1">&#39;myKey&#39;</span><span class="p">));</span>
        <span class="nb">var_dump</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getSession</span><span class="p">()</span><span class="o">-&gt;</span><span class="na">has</span><span class="p">(</span><span class="s1">&#39;myKey&#39;</span><span class="p">));</span> <span class="c1">// bool(false)</span>

        <span class="c1">// Get all the session values</span>
        <span class="nv">$allSessionValues</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">session</span><span class="p">();</span>

    <span class="p">}</span>
<span class="p">}</span>
</pre></div>
</div>
</div>
<div class="section" id="working-with-the-config">
<h2>Working with the config<a class="headerlink" href="#working-with-the-config" title="Permalink to this headline">¶</a></h2>
<p>Using the <tt class="docutils literal"><span class="pre">getConfig()</span></tt> method we can obtain the config array. This config array is the result of ALL the configs returned from all the modules, merged with your application&#8217;s global config.</p>
<div class="highlight-php"><div class="highlight"><pre><span class="o">&lt;?</span><span class="nx">php</span>
<span class="k">namespace</span> <span class="nx">Application\Controller</span><span class="p">;</span>

<span class="k">use</span> <span class="nx">Application\Controller\Shared</span> <span class="k">as</span> <span class="nx">BaseController</span><span class="p">;</span>

<span class="k">class</span> <span class="nc">Blog</span> <span class="k">extends</span> <span class="nx">BaseController</span> <span class="p">{</span>

    <span class="k">public</span> <span class="k">function</span> <span class="nf">configAction</span><span class="p">()</span> <span class="p">{</span>

        <span class="nv">$config</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getConfig</span><span class="p">();</span>

        <span class="k">switch</span><span class="p">(</span><span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;mailer&#39;</span><span class="p">])</span> <span class="p">{</span>

            <span class="k">case</span> <span class="s1">&#39;swift&#39;</span><span class="o">:</span>
                <span class="k">break</span><span class="p">;</span>

            <span class="k">case</span> <span class="s1">&#39;sendgrid&#39;</span><span class="o">:</span>
                <span class="k">break</span><span class="p">;</span>

            <span class="k">case</span> <span class="s1">&#39;mailchimp&#39;</span><span class="o">:</span>
                <span class="k">break</span><span class="p">;</span>

        <span class="p">}</span>
    <span class="p">}</span>
<span class="p">}</span>
</pre></div>
</div>
</div>
<div class="section" id="working-with-the-is-method">
<h2>Working with the is() method<a class="headerlink" href="#working-with-the-is-method" title="Permalink to this headline">¶</a></h2>
<p>The <tt class="docutils literal"><span class="pre">is()</span></tt> method is a very expressive way of coding and has a variety of options you can send to it. The method always returns a boolean as you are saying &#8220;is this the case?&#8221;</p>
<div class="highlight-php"><div class="highlight"><pre><span class="o">&lt;?</span><span class="nx">php</span>
<span class="k">namespace</span> <span class="nx">Application\Controller</span><span class="p">;</span>

<span class="k">use</span> <span class="nx">Application\Controller\Shared</span> <span class="k">as</span> <span class="nx">BaseController</span><span class="p">;</span>

<span class="k">class</span> <span class="nc">Blog</span> <span class="k">extends</span> <span class="nx">BaseController</span> <span class="p">{</span>

    <span class="k">public</span> <span class="k">function</span> <span class="nf">isAction</span><span class="p">()</span> <span class="p">{</span>

        <span class="k">if</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">is</span><span class="p">(</span><span class="s1">&#39;ajax&#39;</span><span class="p">))</span> <span class="p">{}</span>

        <span class="k">if</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">is</span><span class="p">(</span><span class="s1">&#39;post&#39;</span><span class="p">)</span> <span class="p">{}</span>
        <span class="k">if</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">is</span><span class="p">(</span><span class="s1">&#39;patch&#39;</span><span class="p">)</span> <span class="p">{}</span>
        <span class="k">if</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">is</span><span class="p">(</span><span class="s1">&#39;put&#39;</span><span class="p">)</span> <span class="p">{}</span>
        <span class="k">if</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">is</span><span class="p">(</span><span class="s1">&#39;delete&#39;</span><span class="p">)</span> <span class="p">{}</span>

        <span class="c1">// ssl, https, secure: are all the same thing</span>
        <span class="k">if</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">is</span><span class="p">(</span><span class="s1">&#39;ssl&#39;</span><span class="p">)</span> <span class="p">{}</span>
        <span class="k">if</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">is</span><span class="p">(</span><span class="s1">&#39;https&#39;</span><span class="p">)</span> <span class="p">{}</span>
        <span class="k">if</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">is</span><span class="p">(</span><span class="s1">&#39;secure&#39;</span><span class="p">)</span> <span class="p">{}</span>

    <span class="p">}</span>
<span class="p">}</span>
</pre></div>
</div>
</div>
<div class="section" id="getting-the-users-ip-or-useragent">
<h2>Getting the users IP or UserAgent<a class="headerlink" href="#getting-the-users-ip-or-useragent" title="Permalink to this headline">¶</a></h2>
<p>Getting the user&#8217;s IP address or user agent is very trivial.</p>
<div class="highlight-php"><div class="highlight"><pre><span class="o">&lt;?</span><span class="nx">php</span>
<span class="k">namespace</span> <span class="nx">Application\Controller</span><span class="p">;</span>

<span class="k">use</span> <span class="nx">Application\Controller\Shared</span> <span class="k">as</span> <span class="nx">BaseController</span><span class="p">;</span>

<span class="k">class</span> <span class="nc">Blog</span> <span class="k">extends</span> <span class="nx">BaseController</span> <span class="p">{</span>

    <span class="k">public</span> <span class="k">function</span> <span class="nf">userAction</span><span class="p">()</span> <span class="p">{</span>

        <span class="nv">$userIP</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getIP</span><span class="p">();</span>
        <span class="nv">$userAgent</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getUserAgent</span><span class="p">();</span>
    <span class="p">}</span>
<span class="p">}</span>
</pre></div>
</div>
</div>
<div class="section" id="working-with-flash-messages">
<h2>Working with flash messages<a class="headerlink" href="#working-with-flash-messages" title="Permalink to this headline">¶</a></h2>
<p>A flash message is a notification that the user will see on the next page that is rendered. It&#8217;s basically a setting stored in the session so when the user hits the next designated page it will display the message, and then disappear from the session. Flash messages in PPI have different types. These types can be <tt class="docutils literal"><span class="pre">'error'</span></tt>, <tt class="docutils literal"><span class="pre">'warning'</span></tt>, <tt class="docutils literal"><span class="pre">'success'</span></tt>, this will determine the color or styling applied to it. For a success message you&#8217;ll see a positive green message and for an error you&#8217;ll see a negative red message.</p>
<p>Review the following action, it is used to delete a blog item and you&#8217;ll see a different flash message depending on the scenario.</p>
<div class="highlight-php"><div class="highlight"><pre><span class="o">&lt;?</span><span class="nx">php</span>
<span class="k">namespace</span> <span class="nx">Application\Controller</span><span class="p">;</span>

<span class="k">use</span> <span class="nx">Application\Controller\Shared</span> <span class="k">as</span> <span class="nx">BaseController</span><span class="p">;</span>

<span class="k">class</span> <span class="nc">Blog</span> <span class="k">extends</span> <span class="nx">BaseController</span> <span class="p">{</span>

    <span class="k">public</span> <span class="k">function</span> <span class="nf">deleteAction</span><span class="p">()</span> <span class="p">{</span>

        <span class="nv">$blogID</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getPost</span><span class="p">()</span><span class="o">-&gt;</span><span class="na">get</span><span class="p">(</span><span class="s1">&#39;blogID&#39;</span><span class="p">);</span>

        <span class="k">if</span><span class="p">(</span><span class="k">empty</span><span class="p">(</span><span class="nv">$blogID</span><span class="p">))</span> <span class="p">{</span>
            <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">setFlash</span><span class="p">(</span><span class="s1">&#39;error&#39;</span><span class="p">,</span> <span class="s1">&#39;Invalid BlogID Specified&#39;</span><span class="p">);</span>
            <span class="k">return</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">redirectToRoute</span><span class="p">(</span><span class="s1">&#39;Blog_Index&#39;</span><span class="p">);</span>
        <span class="p">}</span>

        <span class="nv">$bs</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getBlogStorage</span><span class="p">();</span>

        <span class="k">if</span><span class="p">(</span><span class="o">!</span><span class="nv">$bs</span><span class="o">-&gt;</span><span class="na">existsByID</span><span class="p">(</span><span class="nv">$blogID</span><span class="p">))</span> <span class="p">{</span>
            <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">setFlash</span><span class="p">(</span><span class="s1">&#39;error&#39;</span><span class="p">,</span> <span class="s1">&#39;This blog ID does not exist&#39;</span><span class="p">);</span>
            <span class="k">return</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">redirectToRoute</span><span class="p">(</span><span class="s1">&#39;Blog_Index&#39;</span><span class="p">);</span>
        <span class="p">}</span>

        <span class="nv">$bs</span><span class="o">-&gt;</span><span class="na">deleteByID</span><span class="p">(</span><span class="nv">$blogID</span><span class="p">);</span>
        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">setFlash</span><span class="p">(</span><span class="s1">&#39;success&#39;</span><span class="p">,</span> <span class="s1">&#39;Your blog post has been deleted&#39;</span><span class="p">);</span>
        <span class="k">return</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">redirectToRoute</span><span class="p">(</span><span class="s1">&#39;Blog_Index&#39;</span><span class="p">);</span>
    <span class="p">}</span>
<span class="p">}</span>
</pre></div>
</div>
</div>
<div class="section" id="getting-the-current-environment">
<h2>Getting the current environment<a class="headerlink" href="#getting-the-current-environment" title="Permalink to this headline">¶</a></h2>
<p>You may want to perform different scenarios based on the site&#8217;s environment. This is a configuration value defined in your global application config. The <tt class="docutils literal"><span class="pre">getEnv()</span></tt> method is how it&#8217;s obtained.</p>
<div class="highlight-php"><div class="highlight"><pre><span class="o">&lt;?</span><span class="nx">php</span>
<span class="k">namespace</span> <span class="nx">Application\Controller</span><span class="p">;</span>

<span class="k">use</span> <span class="nx">Application\Controller\Shared</span> <span class="k">as</span> <span class="nx">BaseController</span><span class="p">;</span>

<span class="k">class</span> <span class="nc">Blog</span> <span class="k">extends</span> <span class="nx">BaseController</span> <span class="p">{</span>

    <span class="k">public</span> <span class="k">function</span> <span class="nf">envAction</span><span class="p">()</span> <span class="p">{</span>

        <span class="nv">$env</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getEnv</span><span class="p">();</span>
        <span class="k">switch</span><span class="p">(</span><span class="nv">$env</span><span class="p">)</span> <span class="p">{</span>
            <span class="k">case</span> <span class="s1">&#39;development&#39;</span><span class="o">:</span>
                <span class="k">break</span><span class="p">;</span>

            <span class="k">case</span> <span class="s1">&#39;staging&#39;</span><span class="o">:</span>
                <span class="k">break</span><span class="p">;</span>

            <span class="k">case</span> <span class="s1">&#39;production&#39;</span><span class="o">:</span>
            <span class="k">default</span><span class="o">:</span>
                <span class="k">break</span><span class="p">;</span>

        <span class="p">}</span>

    <span class="p">}</span>
<span class="p">}</span>
</pre></div>
</div>
</div>
</div>


            
            </div>
        
        </div>
        
    </div>