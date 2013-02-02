


    <div class="container" id="docspage-content">
        
        <div id="action-content" data-prevpage="Controllers" data-prevlink="controllers.html.php"
        >
        
            <div class="content-box docs-page">
                
  <div class="section" id="templating">
<span id="index-0"></span><h1>Templating<a class="headerlink" href="#templating" title="Permalink to this headline">¶</a></h1>
<p>As discovered in the previous chapter, a controller&#8217;s job is to process each HTTP request that hits your web application. Once your controller has finished its processing it usually wants to generate some output content. To achieve this it hands over responsibility to the templating engine. The templating engine will load up the template file you tell it to, and then generate the output you want, his can be in the form of a redirect, HTML webpage output, XML, CSV, JSON; you get the picture!</p>
<p><strong>In this chapter you&#8217;ll learn:</strong></p>
<ul class="simple">
<li>How to create a base template</li>
<li>How to load templates from your controller</li>
<li>How to pass data into templates</li>
<li>How to extend a parent template</li>
<li>How to use template helpers</li>
</ul>
<div class="section" id="base-templates">
<h2>Base Templates<a class="headerlink" href="#base-templates" title="Permalink to this headline">¶</a></h2>
<p><strong>What are base templates?</strong></p>
<p>Why do we need base templates? well you don&#8217;t want to have to repeat HTML over and over again and perform repetative steps for every different type of page you have. There&#8217;s usually some commonalities between the templates and this commonality is your base template. The part that&#8217;s usually different is the content page of your webpage, such as a users profile or a blog post.</p>
<p>So lets see an example of what we call a base template, or somethings referred to as a master template. This is all the HTML structure of your webpage including headers and footers, and the part that&#8217;ll change will be everything inside the page-content section.</p>
<p><strong>Where are they stored?</strong></p>
<p>Base templates are stored in the <tt class="docutils literal"><span class="pre">./app/views/</span></tt> directory. You can have as many base templates as you like in there.</p>
<p>This file is <tt class="docutils literal"><span class="pre">./app/views/base.html.php</span></tt></p>
<p><strong>Example base template:</strong></p>
<div class="highlight-html+php"><div class="highlight"><pre><span class="cp">&lt;!DOCTYPE html&gt;</span>
<span class="nt">&lt;html&gt;</span>
    <span class="nt">&lt;head&gt;</span>
        <span class="nt">&lt;title&gt;</span>Welcome to Symfony!<span class="nt">&lt;/title&gt;</span>
    <span class="nt">&lt;/head&gt;</span>
    <span class="nt">&lt;body&gt;</span>
        <span class="nt">&lt;div</span> <span class="na">id=</span><span class="s">&quot;header&quot;</span><span class="nt">&gt;</span>...<span class="nt">&lt;/div&gt;</span>
        <span class="nt">&lt;div</span> <span class="na">id=</span><span class="s">&quot;page-content&quot;</span><span class="nt">&gt;</span>
            <span class="cp">&lt;?php</span> <span class="nv">$view</span><span class="p">[</span><span class="s1">&#39;slots&#39;</span><span class="p">]</span><span class="o">-&gt;</span><span class="na">output</span><span class="p">(</span><span class="s1">&#39;_content&#39;</span><span class="p">);</span> <span class="cp">?&gt;</span>
        <span class="nt">&lt;/div&gt;</span>
        <span class="nt">&lt;div</span> <span class="na">id=</span><span class="s">&quot;footer&quot;</span><span class="nt">&gt;</span>...<span class="nt">&lt;/div&gt;</span>
    <span class="nt">&lt;/body&gt;</span>
<span class="nt">&lt;/html&gt;</span>
</pre></div>
</div>
<p>Lets recap a little, you see that slots helper outputting something called _content? Well this is actually injecting the resulting output of the CHILD template belonging to this base template. Yes that means we have child templates that extend parent/base templates. This is where things get interesting! Keep on reading.</p>
</div>
<div class="section" id="extending-base-templates">
<h2>Extending Base Templates<a class="headerlink" href="#extending-base-templates" title="Permalink to this headline">¶</a></h2>
<p>On our first line we extend the base template we want. You can extend literally any template you like by specifying its <tt class="docutils literal"><span class="pre">Module:folder:file.format.engine</span></tt> naming syntax. If you miss out the Module and folder sections, such as <tt class="docutils literal"><span class="pre">::base.html.php</span></tt> then it&#8217;s going to take the global route of <tt class="docutils literal"><span class="pre">./app/views/</span></tt>.</p>
<div class="highlight-html+php"><div class="highlight"><pre><span class="cp">&lt;?php</span> <span class="nv">$view</span><span class="o">-&gt;</span><span class="na">extend</span><span class="p">(</span><span class="s1">&#39;::base.html.php&#39;</span><span class="p">);</span> <span class="cp">?&gt;</span>
<span class="nt">&lt;div</span> <span class="na">class=</span><span class="s">&quot;user-registration-page&quot;</span><span class="nt">&gt;</span>
    <span class="nt">&lt;h1&gt;</span>Register for our site<span class="nt">&lt;/h1&gt;</span>
    <span class="nt">&lt;form&gt;</span>...<span class="nt">&lt;/form&gt;</span>
<span class="nt">&lt;/div&gt;</span>
</pre></div>
</div>
</div>
<div class="section" id="the-resulting-output">
<h2>The resulting output<a class="headerlink" href="#the-resulting-output" title="Permalink to this headline">¶</a></h2>
<p>If you remember that the extend call is really just populating a slots section named _content then the injected content into the parent template looks like this.</p>
<div class="highlight-html+php"><div class="highlight"><pre><span class="cp">&lt;!DOCTYPE html&gt;</span>
<span class="nt">&lt;html&gt;</span>
    <span class="nt">&lt;head&gt;</span>
        <span class="nt">&lt;title&gt;</span>Welcome to Symfony!<span class="nt">&lt;/title&gt;</span>
    <span class="nt">&lt;/head&gt;</span>
    <span class="nt">&lt;body&gt;</span>
        <span class="nt">&lt;div</span> <span class="na">id=</span><span class="s">&quot;header&quot;</span><span class="nt">&gt;</span>...<span class="nt">&lt;/div&gt;</span>
        <span class="nt">&lt;div</span> <span class="na">id=</span><span class="s">&quot;page-content&quot;</span><span class="nt">&gt;</span>

            <span class="nt">&lt;div</span> <span class="na">class=</span><span class="s">&quot;user-registration-page&quot;</span><span class="nt">&gt;</span>
                <span class="nt">&lt;h1&gt;</span>Register for our site<span class="nt">&lt;/h1&gt;</span>
                <span class="nt">&lt;form&gt;</span>...<span class="nt">&lt;/form&gt;</span>
            <span class="nt">&lt;/div&gt;</span>

        <span class="nt">&lt;/div&gt;</span>
        <span class="nt">&lt;div</span> <span class="na">id=</span><span class="s">&quot;footer&quot;</span><span class="nt">&gt;</span>...<span class="nt">&lt;/div&gt;</span>
    <span class="nt">&lt;/body&gt;</span>
<span class="nt">&lt;/html&gt;</span>
</pre></div>
</div>
</div>
<div class="section" id="example-scenario">
<h2>Example scenario<a class="headerlink" href="#example-scenario" title="Permalink to this headline">¶</a></h2>
<p>Consider the following scenario. We have the route <tt class="docutils literal"><span class="pre">Blog_Show</span></tt> which executes the action <tt class="docutils literal"><span class="pre">Application:Blog:show</span></tt>. We then load up a template named <tt class="docutils literal"><span class="pre">Application:blog:show.html.php</span></tt> which is designed to show the user their blog post.</p>
<div class="section" id="the-route">
<h3>The route<a class="headerlink" href="#the-route" title="Permalink to this headline">¶</a></h3>
<div class="highlight-yaml"><div class="highlight"><pre><span class="l-Scalar-Plain">Blog_Show</span><span class="p-Indicator">:</span>
    <span class="l-Scalar-Plain">pattern</span><span class="p-Indicator">:</span> <span class="l-Scalar-Plain">/blog/{id}</span>
    <span class="l-Scalar-Plain">defaults</span><span class="p-Indicator">:</span> <span class="p-Indicator">{</span> <span class="nv">_controller</span><span class="p-Indicator">:</span> <span class="s">&quot;Application:Blog:show&quot;</span><span class="p-Indicator">}</span>
</pre></div>
</div>
</div>
<div class="section" id="the-controller">
<h3>The controller<a class="headerlink" href="#the-controller" title="Permalink to this headline">¶</a></h3>
<div class="highlight-php"><div class="highlight"><pre><span class="o">&lt;?</span><span class="nx">php</span>
<span class="k">namespace</span> <span class="nx">Application\Controller</span><span class="p">;</span>

<span class="k">use</span> <span class="nx">Application\Controller\Shared</span> <span class="k">as</span> <span class="nx">BaseController</span><span class="p">;</span>

<span class="k">class</span> <span class="nc">Blog</span> <span class="k">extends</span> <span class="nx">BaseController</span> <span class="p">{</span>

    <span class="k">public</span> <span class="k">function</span> <span class="nf">showAction</span><span class="p">()</span> <span class="p">{</span>

        <span class="nv">$blogID</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getRouteParam</span><span class="p">(</span><span class="s1">&#39;id&#39;</span><span class="p">);</span>
        <span class="nv">$bs</span>     <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getBlogStorage</span><span class="p">();</span>

        <span class="k">if</span><span class="p">(</span><span class="o">!</span><span class="nv">$bs</span><span class="o">-&gt;</span><span class="na">existsByID</span><span class="p">(</span><span class="nv">$blogID</span><span class="p">))</span> <span class="p">{</span>
            <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">setFlash</span><span class="p">(</span><span class="s1">&#39;error&#39;</span><span class="p">,</span> <span class="s1">&#39;Invalid Blog ID&#39;</span><span class="p">);</span>
            <span class="k">return</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">redirectToRoute</span><span class="p">(</span><span class="s1">&#39;Blog_Index&#39;</span><span class="p">);</span>
        <span class="p">}</span>

        <span class="c1">// Get the blog post for this ID</span>
        <span class="nv">$blogPost</span> <span class="o">=</span> <span class="nv">$bs</span><span class="o">-&gt;</span><span class="na">getByID</span><span class="p">(</span><span class="nv">$blogID</span><span class="p">);</span>

        <span class="c1">// Render our blog post page, passing in our $blogPost article to be rendered</span>
        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render</span><span class="p">(</span><span class="s1">&#39;Application:blog:show.html.php&#39;</span><span class="p">,</span> <span class="nb">compact</span><span class="p">(</span><span class="s1">&#39;blogPost&#39;</span><span class="p">));</span>
    <span class="p">}</span>
<span class="p">}</span>
</pre></div>
</div>
</div>
<div class="section" id="the-template">
<h3>The template<a class="headerlink" href="#the-template" title="Permalink to this headline">¶</a></h3>
<p>So the name of the template loaded is Application:blog:show.html.php then this is going to translate to <tt class="docutils literal"><span class="pre">./modules/Application/blog/show.html.php</span></tt>. We also passed in a <tt class="docutils literal"><span class="pre">$blogPost</span></tt> variable which can be used locally within the template that you&#8217;ll see below.</p>
<div class="highlight-html+php"><div class="highlight"><pre><span class="cp">&lt;?php</span> <span class="nv">$view</span><span class="o">-&gt;</span><span class="na">extend</span><span class="p">(</span><span class="s1">&#39;::base.html.php&#39;</span><span class="p">);</span> <span class="cp">?&gt;</span>

<span class="nt">&lt;div</span> <span class="na">class=</span><span class="s">&quot;blog-post-page&quot;</span><span class="nt">&gt;</span>
    <span class="nt">&lt;h1&gt;</span><span class="cp">&lt;?</span><span class="o">=</span><span class="nv">$blogPost</span><span class="o">-&gt;</span><span class="na">getTitle</span><span class="p">();</span><span class="cp">?&gt;</span><span class="nt">&lt;/h1&gt;</span>
    <span class="nt">&lt;p</span> <span class="na">class=</span><span class="s">&quot;created-by&quot;</span><span class="nt">&gt;</span><span class="cp">&lt;?</span><span class="o">=</span><span class="nv">$blogPost</span><span class="o">-&gt;</span><span class="na">getCreatedBy</span><span class="p">();</span><span class="cp">?&gt;</span><span class="nt">&lt;/p&gt;</span>
    <span class="nt">&lt;p</span> <span class="na">class=</span><span class="s">&quot;content&quot;</span><span class="nt">&gt;</span><span class="cp">&lt;?</span><span class="o">=</span><span class="nv">$blogPost</span><span class="o">-&gt;</span><span class="na">getContent</span><span class="p">();</span><span class="cp">?&gt;</span><span class="nt">&lt;/p&gt;</span>
<span class="nt">&lt;/div&gt;</span>
</pre></div>
</div>
</div>
</div>
<div class="section" id="using-the-slots-helper">
<h2>Using the slots helper<a class="headerlink" href="#using-the-slots-helper" title="Permalink to this headline">¶</a></h2>
<p>We have a bunch of template helpers available to you, the helpers are stored in the $view variable, such as <tt class="docutils literal"><span class="pre">$view['slots']</span></tt> or <tt class="docutils literal"><span class="pre">$view['assets']</span></tt>. So what is the purpose of using slots? Well they&#8217;re really for segmenting the templates up into named sections and this allows the child templates to specify content that the parent is going to inject for them.</p>
<p>Review this example it shows a few examples of using the slots helper for various different reasons.</p>
<div class="section" id="the-base-template">
<h3>The base template<a class="headerlink" href="#the-base-template" title="Permalink to this headline">¶</a></h3>
<div class="highlight-html+php"><div class="highlight"><pre><span class="cp">&lt;!DOCTYPE html&gt;</span>
<span class="nt">&lt;html&gt;</span>
    <span class="nt">&lt;head&gt;</span>
        <span class="nt">&lt;meta</span> <span class="na">http-equiv=</span><span class="s">&quot;Content-Type&quot;</span> <span class="na">content=</span><span class="s">&quot;text/html; charset=utf-8&quot;</span> <span class="nt">/&gt;</span>
        <span class="nt">&lt;title&gt;</span><span class="cp">&lt;?php</span> <span class="nv">$view</span><span class="p">[</span><span class="s1">&#39;slots&#39;</span><span class="p">]</span><span class="o">-&gt;</span><span class="na">output</span><span class="p">(</span><span class="s1">&#39;title&#39;</span><span class="p">,</span> <span class="s1">&#39;PPI Skeleton Application&#39;</span><span class="p">)</span> <span class="cp">?&gt;</span><span class="nt">&lt;/title&gt;</span>
    <span class="nt">&lt;/head&gt;</span>
    <span class="nt">&lt;body&gt;</span>
        <span class="nt">&lt;div</span> <span class="na">id=</span><span class="s">&quot;page-content&quot;</span><span class="nt">&gt;</span>
            <span class="cp">&lt;?php</span> <span class="nv">$view</span><span class="p">[</span><span class="s1">&#39;slots&#39;</span><span class="p">]</span><span class="o">-&gt;</span><span class="na">output</span><span class="p">(</span><span class="s1">&#39;_content&#39;</span><span class="p">)</span> <span class="cp">?&gt;</span>
        <span class="nt">&lt;/div&gt;</span>
    <span class="nt">&lt;/body&gt;</span>
<span class="nt">&lt;/html&gt;</span>
</pre></div>
</div>
</div>
<div class="section" id="the-child-template">
<h3>The child template<a class="headerlink" href="#the-child-template" title="Permalink to this headline">¶</a></h3>
<div class="highlight-html+php"><div class="highlight"><pre><span class="cp">&lt;?php</span> <span class="nv">$view</span><span class="o">-&gt;</span><span class="na">extend</span><span class="p">(</span><span class="s1">&#39;::base.html.php&#39;</span><span class="p">);</span> <span class="cp">?&gt;</span>

<span class="nt">&lt;div</span> <span class="na">class=</span><span class="s">&quot;blog-post-page&quot;</span><span class="nt">&gt;</span>
    <span class="nt">&lt;h1&gt;</span><span class="cp">&lt;?</span><span class="o">=</span><span class="nv">$blogPost</span><span class="o">-&gt;</span><span class="na">getTitle</span><span class="p">();</span><span class="cp">?&gt;</span><span class="nt">&lt;/h1&gt;</span>
    <span class="nt">&lt;p</span> <span class="na">class=</span><span class="s">&quot;created-by&quot;</span><span class="nt">&gt;</span><span class="cp">&lt;?</span><span class="o">=</span><span class="nv">$blogPost</span><span class="o">-&gt;</span><span class="na">getCreatedBy</span><span class="p">();</span><span class="cp">?&gt;</span><span class="nt">&lt;/p&gt;</span>
    <span class="nt">&lt;p</span> <span class="na">class=</span><span class="s">&quot;content&quot;</span><span class="nt">&gt;</span><span class="cp">&lt;?</span><span class="o">=</span><span class="nv">$blogPost</span><span class="o">-&gt;</span><span class="na">getContent</span><span class="p">();</span><span class="cp">?&gt;</span><span class="nt">&lt;/p&gt;</span>
<span class="nt">&lt;/div&gt;</span>

<span class="cp">&lt;?php</span> <span class="nv">$view</span><span class="p">[</span><span class="s1">&#39;slots&#39;</span><span class="p">]</span><span class="o">-&gt;</span><span class="na">start</span><span class="p">(</span><span class="s1">&#39;title&#39;</span><span class="p">);</span> <span class="cp">?&gt;</span>
Welcome to the blog page
<span class="cp">&lt;?php</span> <span class="nv">$view</span><span class="p">[</span><span class="s1">&#39;slots&#39;</span><span class="p">]</span><span class="o">-&gt;</span><span class="na">stop</span><span class="p">();</span> <span class="cp">?&gt;</span>
</pre></div>
</div>
<p><strong>What&#8217;s going on?</strong></p>
<p>The slots key we specified first was title and we gave the output method a second parameter, this means when the child template does not specify a slot section named title then it will default to &#8220;PPI Skeleton Application&#8221;.</p>
</div>
</div>
<div class="section" id="using-the-assets-helper">
<h2>Using the assets helper<a class="headerlink" href="#using-the-assets-helper" title="Permalink to this headline">¶</a></h2>
<p>So why do we need an assets helper? Well one main purpose for it is to include asset files from your project&#8217;s <tt class="docutils literal"><span class="pre">./public/</span></tt> folder such as images, css files, javascript files. This is useful because we&#8217;re never hard-coding any baseurl&#8217;s anywhere so it will work on any environment you host it on.</p>
<p>Review this example it shows a few examples of using the slots helper for various different reasons such as including CSS and JS files.</p>
<div class="highlight-html+php"><div class="highlight"><pre><span class="cp">&lt;?php</span> <span class="nv">$view</span><span class="o">-&gt;</span><span class="na">extend</span><span class="p">(</span><span class="s1">&#39;::base.html.php&#39;</span><span class="p">);</span> <span class="cp">?&gt;</span>

<span class="nt">&lt;div</span> <span class="na">class=</span><span class="s">&quot;blog-post-page&quot;</span><span class="nt">&gt;</span>

    <span class="nt">&lt;h1&gt;</span><span class="cp">&lt;?</span><span class="o">=</span><span class="nv">$blogPost</span><span class="o">-&gt;</span><span class="na">getTitle</span><span class="p">();</span><span class="cp">?&gt;</span><span class="nt">&lt;/h1&gt;</span>

    <span class="nt">&lt;img</span> <span class="na">src=</span><span class="s">&quot;</span><span class="cp">&lt;?</span><span class="o">=</span><span class="nv">$view</span><span class="p">[</span><span class="s1">&#39;assets&#39;</span><span class="p">]</span><span class="o">-&gt;</span><span class="na">getUrl</span><span class="p">(</span><span class="s1">&#39;images/blog.png&#39;</span><span class="p">);</span><span class="cp">?&gt;</span><span class="s">&quot;</span> <span class="na">alt=</span><span class="s">&quot;The Blog Image&quot;</span><span class="nt">&gt;</span>

    <span class="nt">&lt;p</span> <span class="na">class=</span><span class="s">&quot;created-by&quot;</span><span class="nt">&gt;</span><span class="cp">&lt;?</span><span class="o">=</span><span class="nv">$blogPost</span><span class="o">-&gt;</span><span class="na">getCreatedBy</span><span class="p">();</span><span class="cp">?&gt;</span><span class="nt">&lt;/p&gt;</span>
    <span class="nt">&lt;p</span> <span class="na">class=</span><span class="s">&quot;content&quot;</span><span class="nt">&gt;</span><span class="cp">&lt;?</span><span class="o">=</span><span class="nv">$blogPost</span><span class="o">-&gt;</span><span class="na">getContent</span><span class="p">();</span><span class="cp">?&gt;</span><span class="nt">&lt;/p&gt;</span>

    <span class="cp">&lt;?php</span> <span class="nv">$view</span><span class="p">[</span><span class="s1">&#39;slots&#39;</span><span class="p">]</span><span class="o">-&gt;</span><span class="na">start</span><span class="p">(</span><span class="s1">&#39;include_js&#39;</span><span class="p">);</span> <span class="cp">?&gt;</span>
    <span class="nt">&lt;script </span><span class="na">type=</span><span class="s">&quot;text/javascript&quot;</span> <span class="na">src=</span><span class="s">&quot;</span><span class="cp">&lt;?</span><span class="o">=</span><span class="nv">$view</span><span class="p">[</span><span class="s1">&#39;assets&#39;</span><span class="p">]</span><span class="o">-&gt;</span><span class="na">getUrl</span><span class="p">(</span><span class="s1">&#39;js/blog.js&#39;</span><span class="p">);</span><span class="cp">?&gt;</span><span class="s">&quot;</span><span class="nt">&gt;&lt;/script&gt;</span>
    <span class="cp">&lt;?php</span> <span class="nv">$view</span><span class="p">[</span><span class="s1">&#39;slots&#39;</span><span class="p">]</span><span class="o">-&gt;</span><span class="na">stop</span><span class="p">();</span> <span class="cp">?&gt;</span>

    <span class="cp">&lt;?php</span> <span class="nv">$view</span><span class="p">[</span><span class="s1">&#39;slots&#39;</span><span class="p">]</span><span class="o">-&gt;</span><span class="na">start</span><span class="p">(</span><span class="s1">&#39;include_css&#39;</span><span class="p">);</span> <span class="cp">?&gt;</span>
    <span class="nt">&lt;link</span> <span class="na">href=</span><span class="s">&quot;</span><span class="cp">&lt;?</span><span class="o">=</span><span class="nv">$view</span><span class="p">[</span><span class="s1">&#39;assets&#39;</span><span class="p">]</span><span class="o">-&gt;</span><span class="na">getUrl</span><span class="p">(</span><span class="s1">&#39;css/blog.css&#39;</span><span class="p">);</span><span class="cp">?&gt;</span><span class="s">&quot;</span> <span class="na">rel=</span><span class="s">&quot;stylesheet&quot;</span><span class="nt">&gt;</span>
    <span class="cp">&lt;?php</span> <span class="nv">$view</span><span class="p">[</span><span class="s1">&#39;slots&#39;</span><span class="p">]</span><span class="o">-&gt;</span><span class="na">stop</span><span class="p">();</span> <span class="cp">?&gt;</span>

<span class="nt">&lt;/div&gt;</span>
</pre></div>
</div>
<p><strong>What&#8217;s going on?</strong></p>
<p>By asking for <tt class="docutils literal"><span class="pre">images/blog.png</span></tt> we&#8217;re basically asking for <tt class="docutils literal"><span class="pre">www.mysite.com/images/blog.png</span></tt>, pretty straight forward right? Our <tt class="docutils literal"><span class="pre">include_css</span></tt> and <tt class="docutils literal"><span class="pre">include_js</span></tt> slots blocks are custom HTML that&#8217;s loading up CSS/JS files just for this paritcular page load. This is great because you can split your application up onto smaller CSS/JS files and only load the required assets for your particular page, rather than having to bundle all your CSS into the one file.</p>
</div>
<div class="section" id="using-the-router-helper">
<h2>Using the router helper<a class="headerlink" href="#using-the-router-helper" title="Permalink to this headline">¶</a></h2>
<p>What is a router helper? The router help is a nice PHP class with routing related methods on it that you can use while you&#8217;re building PHP templates for your application.</p>
<p>What&#8217;s it useful for? The most common use for this is to perform a technique commonly known as reverse routing. Basically this is the process of taking a route key and turning that into a URL, rather than the standard process of having a URL and that translate into a route to become dispatched.</p>
<p>Why is reverse routing needed? Lets take the Blog_Show route we made earlier in the routing section. The syntax of that URI would be like: <tt class="docutils literal"><span class="pre">/blog/show/{title}</span></tt>, so rather than having numerous HTML links all manually referring to <tt class="docutils literal"><span class="pre">/blog/show/my-title</span></tt> we always refer to its route key instead, that way if we ever want to change the URI to something like <tt class="docutils literal"><span class="pre">/blog/post/{title}</span></tt> the templating layer of your application won&#8217;t care because that change has been centrally maintained in your module&#8217;s routes file.</p>
<p>Here are some examples of reverse routing using the routes helper</p>
<div class="highlight-html+php"><div class="highlight"><pre><span class="nt">&lt;a</span> <span class="na">href=</span><span class="s">&quot;</span><span class="cp">&lt;?</span><span class="o">=</span><span class="nv">$view</span><span class="p">[</span><span class="s1">&#39;router&#39;</span><span class="p">]</span><span class="o">-&gt;</span><span class="na">generate</span><span class="p">(</span><span class="s1">&#39;About_Page&#39;</span><span class="p">);</span><span class="cp">?&gt;</span><span class="s">&quot;</span><span class="nt">&gt;</span>About Page<span class="nt">&lt;/a&gt;</span>

<span class="nt">&lt;p&gt;</span>User List<span class="nt">&lt;/p&gt;</span>
<span class="nt">&lt;ul&gt;</span>
<span class="cp">&lt;?php</span> <span class="k">foreach</span><span class="p">(</span><span class="nv">$users</span> <span class="k">as</span> <span class="nv">$user</span><span class="p">)</span><span class="o">:</span> <span class="cp">?&gt;</span>
    <span class="nt">&lt;li&gt;&lt;a</span> <span class="na">href=</span><span class="s">&quot;</span><span class="cp">&lt;?</span><span class="o">=</span><span class="nv">$view</span><span class="p">[</span><span class="s1">&#39;router&#39;</span><span class="p">]</span><span class="o">-&gt;</span><span class="na">generate</span><span class="p">(</span><span class="s1">&#39;User_Profile&#39;</span><span class="p">,</span> <span class="k">array</span><span class="p">(</span><span class="s1">&#39;id&#39;</span> <span class="o">=&gt;</span> <span class="nv">$user</span><span class="o">-&gt;</span><span class="na">getID</span><span class="p">()));</span> <span class="cp">?&gt;</span><span class="s">&quot;</span><span class="nt">&gt;</span><span class="cp">&lt;?</span><span class="o">=</span><span class="nv">$view</span><span class="o">-&gt;</span><span class="na">escape</span><span class="p">(</span><span class="nv">$user</span><span class="o">-&gt;</span><span class="na">getName</span><span class="p">());</span><span class="cp">?&gt;</span><span class="nt">&lt;/a&gt;&lt;/li&gt;</span>
<span class="cp">&lt;?php</span> <span class="k">endforeach</span><span class="p">;</span> <span class="cp">?&gt;</span>
<span class="nt">&lt;/ul&gt;</span>
</pre></div>
</div>
<p>The output would be something like this</p>
<div class="highlight-html+php"><div class="highlight"><pre><span class="nt">&lt;a</span> <span class="na">href=</span><span class="s">&quot;/about&quot;</span><span class="nt">&gt;</span>About Page<span class="nt">&lt;/a&gt;</span>

<span class="nt">&lt;p&gt;</span>User List<span class="nt">&lt;/p&gt;</span>
<span class="nt">&lt;ul&gt;</span>
    <span class="nt">&lt;li&gt;&lt;a</span> <span class="na">href=</span><span class="s">&quot;/user/profile?id=23&quot;</span><span class="nt">&gt;</span>PPI User<span class="nt">&lt;/a&gt;&lt;/li&gt;</span>
    <span class="nt">&lt;li&gt;&lt;a</span> <span class="na">href=</span><span class="s">&quot;/user/profile?id=87675&quot;</span><span class="nt">&gt;</span>Another PPI User<span class="nt">&lt;/a&gt;&lt;/li&gt;</span>
<span class="nt">&lt;/ul&gt;</span>
</pre></div>
</div>
</div>
</div>


            
            </div>
        
        </div>
        
    </div>