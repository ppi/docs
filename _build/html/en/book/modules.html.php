


    <div class="container" id="docspage-content">
        
        <div id="action-content" data-prevpage="Skeleton Application" data-prevlink="application.html.php" data-nextpage="Routing" data-nextlink="routing.html.php"
        >
        
            <div class="content-box docs-page">
                
  <div class="section" id="modules">
<span id="index-0"></span><h1>Modules<a class="headerlink" href="#modules" title="Permalink to this headline">¶</a></h1>
<p>By default, one module is provided with the SkeletonApp, named <strong>Application</strong>. It provides a simple route pointing to the homepage. A simple controller to handle the &#8220;home&#8221; page of the application. This demonstrates using routes, controllers and views within your module.</p>
<div class="section" id="module-structure">
<h2>Module Structure<a class="headerlink" href="#module-structure" title="Permalink to this headline">¶</a></h2>
<p>Your module starts with Module.php. You can have configuration on your module. Your can have routes which result in controllers getting dispatched. Your controllers can render view templates.</p>
<div class="highlight-bash"><div class="highlight"><pre>modules/

    Application/

        Module.php

        Controller/
            Index.php

        resources/

            views/
                index/index.html.php
                index/list.html.php

            config/
                config.php
                routes.yml
</pre></div>
</div>
</div>
<div class="section" id="the-module-php-class">
<h2>The Module.php class<a class="headerlink" href="#the-module-php-class" title="Permalink to this headline">¶</a></h2>
<p>Every PPI module looks for a <tt class="docutils literal"><span class="pre">Module.php</span></tt> class file, this is the starting point for your module.</p>
<div class="highlight-php"><div class="highlight"><pre><span class="o">&lt;?</span><span class="nx">php</span>
<span class="k">namespace</span> <span class="nx">Application</span><span class="p">;</span>

<span class="k">use</span> <span class="nx">PPI\Module\Module</span> <span class="k">as</span> <span class="nx">BaseModule</span>
<span class="k">use</span> <span class="nx">PPI\Autoload</span><span class="p">;</span>

<span class="k">class</span> <span class="nc">Module</span> <span class="k">extends</span> <span class="nx">BaseModule</span> <span class="p">{</span>

    <span class="k">protected</span> <span class="nv">$_moduleName</span> <span class="o">=</span> <span class="s1">&#39;Application&#39;</span><span class="p">;</span>

    <span class="k">function</span> <span class="nf">init</span><span class="p">(</span><span class="nv">$e</span><span class="p">)</span> <span class="p">{</span>
        <span class="nx">Autoload</span><span class="o">::</span><span class="na">add</span><span class="p">(</span><span class="nx">__NAMESPACE__</span><span class="p">,</span> <span class="nb">dirname</span><span class="p">(</span><span class="nx">__DIR__</span><span class="p">));</span>
    <span class="p">}</span>

<span class="p">}</span>
</pre></div>
</div>
</div>
<div class="section" id="init">
<h2>Init<a class="headerlink" href="#init" title="Permalink to this headline">¶</a></h2>
<p>The above code shows you the Module class, and the all important <tt class="docutils literal"><span class="pre">init()</span></tt> method. Why is it important? If you remember from The Skeleton Application section previously, we have defined in our <tt class="docutils literal"><span class="pre">modules.config.php</span></tt> config file an activeModules option, when PPI is booting up the modules defined activeModules it looks for each module&#8217;s init() method and calls it.</p>
<p>The <tt class="docutils literal"><span class="pre">init()</span></tt> method is run for every page request, and should not perform anything heavy. It is considered bad practice to utilize these methods for setting up or configuring instances of application resources such as a database connection, application logger, or mailer.</p>
</div>
<div class="section" id="your-modules-resources">
<h2>Your modules resources<a class="headerlink" href="#your-modules-resources" title="Permalink to this headline">¶</a></h2>
<p><tt class="docutils literal"><span class="pre">/Application/resources/</span></tt> is where non-PHP-class files live such as config files (<tt class="docutils literal"><span class="pre">resources/config</span></tt>) and views (<tt class="docutils literal"><span class="pre">resources/views</span></tt>). We encourage you to put your own custom config files in <tt class="docutils literal"><span class="pre">/resources/config/</span></tt> too.</p>
</div>
<div class="section" id="configuration">
<h2>Configuration<a class="headerlink" href="#configuration" title="Permalink to this headline">¶</a></h2>
<p>Expanding on from the previous code example, we&#8217;re now adding a <tt class="docutils literal"><span class="pre">getConfig()</span></tt> method. This must return a raw php array. All the modules with getConfig() defined on them will be merged together to create &#8216;modules config&#8217; and this is merged with your global app&#8217;s configuration file at <tt class="docutils literal"><span class="pre">/app/app.config.php</span></tt>. Now from any controller you can get access to this config by doing <tt class="docutils literal"><span class="pre">$this-&gt;getConfig()</span></tt>. More examples on this later in the Controllers section.</p>
<div class="highlight-php"><div class="highlight"><pre><span class="o">&lt;?</span><span class="nx">php</span>
<span class="k">class</span> <span class="nc">Module</span> <span class="k">extends</span> <span class="nx">BaseModule</span> <span class="p">{</span>

<span class="k">protected</span> <span class="nv">$_moduleName</span> <span class="o">=</span> <span class="s1">&#39;Application&#39;</span><span class="p">;</span>

    <span class="k">public</span> <span class="k">function</span> <span class="nf">init</span><span class="p">(</span><span class="nv">$e</span><span class="p">)</span> <span class="p">{</span>
        <span class="nx">Autoload</span><span class="o">::</span><span class="na">add</span><span class="p">(</span><span class="nx">__NAMESPACE__</span><span class="p">,</span> <span class="nb">dirname</span><span class="p">(</span><span class="nx">__DIR__</span><span class="p">));</span>
    <span class="p">}</span>

    <span class="k">public</span> <span class="k">function</span> <span class="nf">getConfig</span><span class="p">()</span> <span class="p">{</span>
        <span class="k">return</span> <span class="k">include</span><span class="p">(</span><span class="nx">__DIR__</span> <span class="o">.</span> <span class="s1">&#39;/resources/config/config.php&#39;</span><span class="p">);</span>
    <span class="p">}</span>

<span class="p">}</span>
</pre></div>
</div>
</div>
<div class="section" id="routing">
<h2>Routing<a class="headerlink" href="#routing" title="Permalink to this headline">¶</a></h2>
<p>The getRoutes() method currently is re-using the Symfony2 routing component. It needs to return a Symfony RouteCollection instance. This means you can setup your routes using PHP, YAML or XML.</p>
<div class="highlight-php"><div class="highlight"><pre><span class="k">class</span> <span class="nc">Module</span> <span class="k">extends</span> <span class="nx">BaseModule</span> <span class="p">{</span>

    <span class="k">protected</span> <span class="nv">$_moduleName</span> <span class="o">=</span> <span class="s1">&#39;Application&#39;</span><span class="p">;</span>

    <span class="k">public</span> <span class="k">function</span> <span class="nf">init</span><span class="p">(</span><span class="nv">$e</span><span class="p">)</span> <span class="p">{</span>
        <span class="nx">Autoload</span><span class="o">::</span><span class="na">add</span><span class="p">(</span><span class="nx">__NAMESPACE__</span><span class="p">,</span> <span class="nb">dirname</span><span class="p">(</span><span class="nx">__DIR__</span><span class="p">));</span>
    <span class="p">}</span>

    <span class="sd">/**</span>
<span class="sd">    * Get the configuration for this module</span>
<span class="sd">    *</span>
<span class="sd">    * @return array</span>
<span class="sd">    */</span>
    <span class="k">public</span> <span class="k">function</span> <span class="nf">getConfig</span><span class="p">()</span> <span class="p">{</span>
        <span class="k">return</span> <span class="k">include</span><span class="p">(</span><span class="nx">__DIR__</span> <span class="o">.</span> <span class="s1">&#39;/resources/config/config.php&#39;</span><span class="p">);</span>
    <span class="p">}</span>

    <span class="sd">/**</span>
<span class="sd">    * Get the routes for this module, in YAML format.</span>
<span class="sd">    *</span>
<span class="sd">    * @return \Symfony\Component\Routing\RouteCollection</span>
<span class="sd">    */</span>
    <span class="k">public</span> <span class="k">function</span> <span class="nf">getRoutes</span><span class="p">()</span> <span class="p">{</span>
        <span class="k">return</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">loadYamlRoutes</span><span class="p">(</span><span class="nx">__DIR__</span> <span class="o">.</span> <span class="s1">&#39;/resources/config/routes.yml&#39;</span><span class="p">);</span>
    <span class="p">}</span>

<span class="p">}</span>
</pre></div>
</div>
</div>
<div class="section" id="conclusion">
<h2>Conclusion<a class="headerlink" href="#conclusion" title="Permalink to this headline">¶</a></h2>
<p>So, what have we learnt in this section so far? We learnt how to initialize our module, and how to obtain configuration options and routes from it.</p>
<p>PPI will boot up all the modules and call the <tt class="docutils literal"><span class="pre">getRoutes()</span></tt> method on them all. It will merge the results together and match them against a request URI such as <tt class="docutils literal"><span class="pre">/blog/my-blog-title</span></tt>. When a matching route is found it dispatches the controller specified in that route.</p>
<p>Lets move onto the Routing section to check out what happens next.</p>
</div>
</div>


            
            </div>
        
        </div>
        
    </div>