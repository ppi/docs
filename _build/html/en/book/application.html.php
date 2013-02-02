


    <div class="container" id="docspage-content">
        
        <div id="action-content" data-nextpage="Modules" data-nextlink="modules.html.php"
        >
        
            <div class="content-box docs-page">
                
  <div class="section" id="skeleton-application">
<h1>Skeleton Application<a class="headerlink" href="#skeleton-application" title="Permalink to this headline">¶</a></h1>
<p>First, lets review the file structure of the PPI skeleton application that we have pre-built for you to get up and running as quickly as possible.:</p>
<div class="highlight-python"><pre>www/ &lt;- your web root directory

skeleton/ &lt;- the unpacked archive
    app/
        app.config.php
        cache/
        views/
        ...

    public/
        index.php
        css/
        js/
        images/
        ...

    modules/
        Application/
            Module.php
            Controller/
            resources/
                config/
                views/
                ...</pre>
</div>
<p>Lets break it down into parts:</p>
<div class="section" id="the-public-folder">
<h2>The public folder<a class="headerlink" href="#the-public-folder" title="Permalink to this headline">¶</a></h2>
<p>The structure above shows you the <tt class="docutils literal"><span class="pre">/public/</span></tt> folder. Anything outside of <tt class="docutils literal"><span class="pre">/public/</span></tt> i.e: all your business code will be secure from direct URL access. In your development environment you don&#8217;t need a virtualhost file, you can directly access your application like so: <a class="reference external" href="http://localhost/skeleton/public/">http://localhost/skeleton/public/</a>. In your production environment this will be <a class="reference external" href="http://www.mysite.com/">http://www.mysite.com/</a>. All your publicly available asset files should be here, CSS, JS, Images.</p>
</div>
<div class="section" id="the-public-index-php-file">
<h2>The public index.php file<a class="headerlink" href="#the-public-index-php-file" title="Permalink to this headline">¶</a></h2>
<p>The /public/index.php is also known are your bootstrap file, or front controller is explained in-depth below</p>
<div class="highlight-php"><div class="highlight"><pre><span class="o">&lt;?</span><span class="nx">php</span>

<span class="c1">// All relative paths start from the main directory, not from /public/</span>
<span class="nb">chdir</span><span class="p">(</span><span class="nb">dirname</span><span class="p">(</span><span class="nx">__DIR__</span><span class="p">));</span>

<span class="c1">// Lets include PPI</span>
<span class="k">include</span><span class="p">(</span><span class="s1">&#39;app/init.php&#39;</span><span class="p">);</span>

<span class="c1">// Initialise our PPI App</span>
<span class="nv">$app</span> <span class="o">=</span> <span class="k">new</span> <span class="nx">PPI\App</span><span class="p">();</span>
<span class="nv">$app</span><span class="o">-&gt;</span><span class="na">moduleConfig</span> <span class="o">=</span> <span class="k">include</span> <span class="s1">&#39;app/modules.config.php&#39;</span><span class="p">;</span>
<span class="nv">$app</span><span class="o">-&gt;</span><span class="na">config</span> <span class="o">=</span> <span class="k">include</span> <span class="s1">&#39;app/app.config.php&#39;</span><span class="p">;</span>

<span class="c1">// If you are using the DataSource component, enable this</span>
<span class="c1">//$app-&gt;useDataSource = true;</span>

<span class="nv">$app</span><span class="o">-&gt;</span><span class="na">boot</span><span class="p">()</span><span class="o">-&gt;</span><span class="na">dispatch</span><span class="p">();</span>
</pre></div>
</div>
<p>If you uncomment the <tt class="docutils literal"><span class="pre">useDataSource</span></tt> line, it is going to look for your <tt class="docutils literal"><span class="pre">/app/datasource.config.php</span></tt> and load that into the DataSource component for you. Databases are not a requirement in PPI so if you dont need one then you wont need to bother about this. More in-depth documentation about this in the DataSource chapter.</p>
</div>
<div class="section" id="the-app-folder">
<h2>The app folder<a class="headerlink" href="#the-app-folder" title="Permalink to this headline">¶</a></h2>
<p>This is where all your apps global items go such as app config, datasource config and modules config and global templates (views). You wont need to touch these out-of-the-box but it allows for greater flexibility in the future if you need it.</p>
</div>
<div class="section" id="the-app-config-php-file">
<h2>The app.config.php file<a class="headerlink" href="#the-app-config-php-file" title="Permalink to this headline">¶</a></h2>
<p>Looking at the example config file below, you can control things here such as the environment, templating engine and datasource connection.</p>
<div class="highlight-php"><div class="highlight"><pre><span class="o">&lt;?</span><span class="nx">php</span>
<span class="nv">$config</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span>
    <span class="s1">&#39;environment&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;development&#39;</span><span class="p">,</span> <span class="c1">// &lt;-- Change this depending on your environment</span>
    <span class="s1">&#39;templating.engine&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;php&#39;</span><span class="p">,</span> <span class="c1">// &lt;-- The default templating engine</span>
    <span class="s1">&#39;datasource.connections&#39;</span> <span class="o">=&gt;</span> <span class="k">include</span> <span class="p">(</span><span class="nx">__DIR__</span> <span class="o">.</span> <span class="s1">&#39;/datasource.config.php&#39;</span><span class="p">)</span>
<span class="p">);</span>

<span class="c1">// Are we in debug mode ?</span>
<span class="k">if</span><span class="p">(</span><span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;environment&#39;</span><span class="p">]</span> <span class="o">!==</span> <span class="s1">&#39;development&#39;</span><span class="p">)</span> <span class="p">{</span> <span class="c1">// &lt;-- You can also check the env from your controller using</span>
    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">getEnv</span><span class="p">()</span>
    <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;debug&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;environment&#39;</span><span class="p">]</span> <span class="o">===</span> <span class="s1">&#39;development&#39;</span><span class="p">;</span>
    <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;cache_dir&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="nx">__DIR__</span> <span class="o">.</span> <span class="s1">&#39;/cache&#39;</span><span class="p">;</span>
<span class="p">}</span>

<span class="k">return</span> <span class="nv">$config</span><span class="p">;</span> <span class="c1">// Very important</span>
</pre></div>
</div>
<p>The <tt class="docutils literal"><span class="pre">return</span> <span class="pre">$config</span></tt> line gets pulled into your <tt class="docutils literal"><span class="pre">index.php</span></tt>&#8216;s <tt class="docutils literal"><span class="pre">$app-&gt;config</span></tt> variable.</p>
</div>
<div class="section" id="the-modules-config-php-file">
<h2>The modules.config.php file<a class="headerlink" href="#the-modules-config-php-file" title="Permalink to this headline">¶</a></h2>
<p>The example below shows that you can control which modules are active and a list of directories module_paths that PPI will scan for your modules. Pay close attention to the order in which your modules are loaded. If one of your modules relies on resources loaded by another module. Make sure the module loading the resources is loaded before the others that depend upon it.</p>
<div class="highlight-php"><div class="highlight"><pre><span class="o">&lt;?</span><span class="nx">php</span>
<span class="k">return</span> <span class="k">array</span><span class="p">(</span>
    <span class="s1">&#39;activeModules&#39;</span>   <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(</span><span class="s1">&#39;Application&#39;</span><span class="p">,</span> <span class="s1">&#39;User&#39;</span><span class="p">),</span>
    <span class="s1">&#39;listenerOptions&#39;</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(</span><span class="s1">&#39;module_paths&#39;</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(</span><span class="s1">&#39;./modules&#39;</span><span class="p">)),</span>
<span class="p">);</span>
</pre></div>
</div>
<p>Note that this file returns an array too, which is assigned against your <tt class="docutils literal"><span class="pre">index.php</span></tt>&#8216;s $app-&gt;moduleConfig variable</p>
</div>
<div class="section" id="the-app-views-folder">
<h2>The app/views folder<a class="headerlink" href="#the-app-views-folder" title="Permalink to this headline">¶</a></h2>
<p>This folder is your applications global views folder. A global view is one that a multitude of other module views extend from. A good example of this is your website&#8217;s template file. The following is an example of <tt class="docutils literal"><span class="pre">/app/views/base.html.php</span></tt>:</p>
<div class="highlight-html+php"><div class="highlight"><pre><span class="nt">&lt;html&gt;</span>
    <span class="nt">&lt;body&gt;</span>
        <span class="nt">&lt;h1&gt;</span>My website<span class="nt">&lt;/h1&gt;</span>
        <span class="nt">&lt;div</span> <span class="na">class=</span><span class="s">&quot;content&quot;</span><span class="nt">&gt;</span>
            <span class="cp">&lt;?php</span> <span class="nv">$view</span><span class="p">[</span><span class="s1">&#39;slots&#39;</span><span class="p">]</span><span class="o">-&gt;</span><span class="na">output</span><span class="p">(</span><span class="s1">&#39;_content&#39;</span><span class="p">);</span> <span class="cp">?&gt;</span>
        <span class="nt">&lt;/div&gt;</span>
    <span class="nt">&lt;/body&gt;</span>
<span class="nt">&lt;/html&gt;</span>
</pre></div>
</div>
<p>You&#8217;ll notice later on in the Templating section to reference and extend a global template file, you will use the following syntax in your modules template.</p>
<div class="highlight-html+php"><div class="highlight"><pre><span class="cp">&lt;?php</span> <span class="nv">$view</span><span class="o">-&gt;</span><span class="na">extend</span><span class="p">(</span><span class="s1">&#39;::base.html.php&#39;</span><span class="p">);</span> <span class="cp">?&gt;</span>
</pre></div>
</div>
<p>Now everything from your module template will be applied into your base.html.php files _content section demonstrated above.</p>
</div>
<div class="section" id="the-modules-folder">
<h2>The modules folder<a class="headerlink" href="#the-modules-folder" title="Permalink to this headline">¶</a></h2>
<p>This is where we get stuck into the real details, we&#8217;re going into the <tt class="docutils literal"><span class="pre">/modules/</span></tt> folder. Click the next section to proceed</p>
</div>
</div>


            
            </div>
        
        </div>
        
    </div>