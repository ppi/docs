


    <div class="container" id="docspage-content">
        
        <div id="action-content" data-prevpage="Modules" data-prevlink="modules.html.php" data-nextpage="Controllers" data-nextlink="controllers.html.php"
        >
        
            <div class="content-box docs-page">
                
  <div class="section" id="routing">
<span id="index-0"></span><h1>Routing<a class="headerlink" href="#routing" title="Permalink to this headline">¶</a></h1>
<p>Routes are the rules that tell the framework what URLs map to what area of your application. The routing here is simple and expressive. We are using the Symfony2 routing component here, this means if you&#8217;re a Symfony2 developer you already know what you&#8217;re doing. If you don&#8217;t know Symfony2 already, then learning the routes here will allow you to read routes from existing Symfony2 bundles out there in the wild. It&#8217;s really a win/win situation.</p>
<p>Routes are an integral part of web application development. They make way for nice clean urls such as <tt class="docutils literal"><span class="pre">/blog/view/5543</span></tt> instead of something like <tt class="docutils literal"><span class="pre">/blog.php?Action=view&amp;article=5543</span></tt>.</p>
<p>By reading this routing section you&#8217;ll be able to:</p>
<ul class="simple">
<li>Create beautiful clean routes</li>
<li>Create routes that take in different parameters</li>
<li>Specify complex requirements for your parameters</li>
<li>Generate URLs within your controllers</li>
<li>Redirect to routes within your controllers</li>
</ul>
<div class="section" id="the-details">
<h2>The Details<a class="headerlink" href="#the-details" title="Permalink to this headline">¶</a></h2>
<p>Lets talk about the structure of a route, you have a route name, pattern, defaults and requirements.</p>
<div class="section" id="name">
<h3>Name<a class="headerlink" href="#name" title="Permalink to this headline">¶</a></h3>
<p>This is a symbolic name to easily refer to this actual from different contexts in your application. Examples of route names are <tt class="docutils literal"><span class="pre">Homepage</span></tt>, <tt class="docutils literal"><span class="pre">Blog_View</span></tt>, <tt class="docutils literal"><span class="pre">Profile_Edit</span></tt>. These are extremely useful if you want to just redirect a user to a page like the login page, you can redirect them to User_Login. If you are in a template file and want to generate a link you can refer to the route name and it will be created for you. The good part about this is you can maintain the routes via your <tt class="docutils literal"><span class="pre">routes.yml</span></tt> file and your entire system updates.</p>
</div>
<div class="section" id="pattern">
<h3>Pattern<a class="headerlink" href="#pattern" title="Permalink to this headline">¶</a></h3>
<p>This is the URI pattern that if present will activate your route. In this example we&#8217;re targeting the homepage. This is where you can specify params like <tt class="docutils literal"><span class="pre">{id}</span></tt> or <tt class="docutils literal"><span class="pre">{username}</span></tt>. You can make URLs like <tt class="docutils literal"><span class="pre">/article/{id}</span></tt> or <tt class="docutils literal"><span class="pre">/profile/{username}</span></tt>.</p>
</div>
<div class="section" id="defaults">
<h3>Defaults<a class="headerlink" href="#defaults" title="Permalink to this headline">¶</a></h3>
<p>This is the important part, The syntax is <tt class="docutils literal"><span class="pre">Module:Controller:action</span></tt>. So if you specify <tt class="docutils literal"><span class="pre">Application:Blog:show</span></tt> then this will execute the following class path: <tt class="docutils literal"><span class="pre">/modules/Application/Controller/Blog-&gt;showAction()</span></tt>. Notice how the method has a suffix of Action, this is so you can have lots of methods on your controller but only the ones ending in <tt class="docutils literal"><span class="pre">Action()</span></tt> will be executable from a route.</p>
</div>
<div class="section" id="requirements">
<h3>Requirements<a class="headerlink" href="#requirements" title="Permalink to this headline">¶</a></h3>
<p>This is where you can specify things like the request method being POST or PUT. You can also specify rules for the parameters you created above in the pattern section. Such as <tt class="docutils literal"><span class="pre">{id}</span></tt> being numeric, or <tt class="docutils literal"><span class="pre">{lang}</span></tt> being in a whitelist of values such as <tt class="docutils literal"><span class="pre">en|de|pt</span></tt>.</p>
<p>With all this knowledge in mind, take a look at all the different examples of routes below and come back up here for reference.</p>
</div>
</div>
<div class="section" id="basic-routes">
<h2>Basic Routes<a class="headerlink" href="#basic-routes" title="Permalink to this headline">¶</a></h2>
<div class="highlight-yaml"><div class="highlight"><pre><span class="l-Scalar-Plain">Homepage</span><span class="p-Indicator">:</span>
<span class="l-Scalar-Plain">pattern</span><span class="p-Indicator">:</span> <span class="l-Scalar-Plain">/</span>
<span class="l-Scalar-Plain">defaults</span><span class="p-Indicator">:</span> <span class="p-Indicator">{</span> <span class="nv">_controller</span><span class="p-Indicator">:</span> <span class="s">&quot;Application:Index:index&quot;</span><span class="p-Indicator">}</span>

<span class="l-Scalar-Plain">Blog_Index</span><span class="p-Indicator">:</span>
<span class="l-Scalar-Plain">pattern</span><span class="p-Indicator">:</span> <span class="l-Scalar-Plain">/blog</span>
<span class="l-Scalar-Plain">defaults</span><span class="p-Indicator">:</span> <span class="p-Indicator">{</span> <span class="nv">_controller</span><span class="p-Indicator">:</span> <span class="s">&quot;Application:Blog:index&quot;</span><span class="p-Indicator">}</span>
</pre></div>
</div>
</div>
<div class="section" id="routes-with-parameters">
<h2>Routes with parameters<a class="headerlink" href="#routes-with-parameters" title="Permalink to this headline">¶</a></h2>
<p>The following example is basically <tt class="docutils literal"><span class="pre">/blog/*</span></tt> where the wildcard is the value given to title. If the URL was <tt class="docutils literal"><span class="pre">/blog/using-ppi2</span></tt> then the title variable gets the value <tt class="docutils literal"><span class="pre">using-ppi2</span></tt>, which you can see being used in the Example Controller section below.</p>
<div class="highlight-yaml"><div class="highlight"><pre><span class="l-Scalar-Plain">Blog_Show</span><span class="p-Indicator">:</span>
<span class="l-Scalar-Plain">pattern</span><span class="p-Indicator">:</span> <span class="l-Scalar-Plain">/blog/{title}</span>
<span class="l-Scalar-Plain">defaults</span><span class="p-Indicator">:</span> <span class="p-Indicator">{</span> <span class="nv">_controller</span><span class="p-Indicator">:</span> <span class="s">&quot;Application:Blog:show&quot;</span><span class="p-Indicator">}</span>
</pre></div>
</div>
<p>This example optionally looks for the <tt class="docutils literal"><span class="pre">{pageNum}</span></tt> parameter, if it&#8217;s not found it defaults to <tt class="docutils literal"><span class="pre">1</span></tt>.</p>
<div class="highlight-yaml"><div class="highlight"><pre><span class="l-Scalar-Plain">Blog_Show</span><span class="p-Indicator">:</span>
<span class="l-Scalar-Plain">pattern</span><span class="p-Indicator">:</span> <span class="l-Scalar-Plain">/blog/{pageNum}</span>
<span class="l-Scalar-Plain">defaults</span><span class="p-Indicator">:</span> <span class="p-Indicator">{</span> <span class="nv">_controller</span><span class="p-Indicator">:</span> <span class="s">&quot;Application:Blog:index&quot;</span><span class="p-Indicator">,</span> <span class="nv">pageNum</span><span class="p-Indicator">:</span> <span class="nv">1</span><span class="p-Indicator">}</span>
</pre></div>
</div>
</div>
<div class="section" id="routes-with-requirements">
<h2>Routes with requirements<a class="headerlink" href="#routes-with-requirements" title="Permalink to this headline">¶</a></h2>
<p>Only form submits using <tt class="docutils literal"><span class="pre">POST</span></tt> will trigger this route. This means you dont have to check this kind of stuff in your controller.</p>
<div class="highlight-yaml"><div class="highlight"><pre><span class="l-Scalar-Plain">Blog_EditSave</span><span class="p-Indicator">:</span>
<span class="l-Scalar-Plain">pattern</span><span class="p-Indicator">:</span> <span class="l-Scalar-Plain">/blog/edit/{id}</span>
<span class="l-Scalar-Plain">defaults</span><span class="p-Indicator">:</span> <span class="p-Indicator">{</span> <span class="nv">_controller</span><span class="p-Indicator">:</span> <span class="s">&quot;Application:Blog:edit&quot;</span><span class="p-Indicator">}</span>
<span class="l-Scalar-Plain">requirements</span><span class="p-Indicator">:</span>
    <span class="l-Scalar-Plain">_method</span><span class="p-Indicator">:</span> <span class="l-Scalar-Plain">POST</span>
</pre></div>
</div>
<p>Checking if the <tt class="docutils literal"><span class="pre">{pageNum}</span></tt> parameter is numerical. Checking if the <tt class="docutils literal"><span class="pre">{lang}</span></tt> parameter is <tt class="docutils literal"><span class="pre">en</span></tt> or <tt class="docutils literal"><span class="pre">de</span></tt>.</p>
<div class="highlight-yaml"><div class="highlight"><pre><span class="l-Scalar-Plain">Blog_Show</span><span class="p-Indicator">:</span>
<span class="l-Scalar-Plain">pattern</span><span class="p-Indicator">:</span> <span class="l-Scalar-Plain">/blog/{lang}/{pageNum}</span>
<span class="l-Scalar-Plain">defaults</span><span class="p-Indicator">:</span> <span class="p-Indicator">{</span> <span class="nv">_controller</span><span class="p-Indicator">:</span> <span class="s">&quot;Application:Blog:index&quot;</span><span class="p-Indicator">,</span> <span class="nv">pageNum</span><span class="p-Indicator">:</span> <span class="nv">1</span><span class="p-Indicator">,</span> <span class="nv">lang</span><span class="p-Indicator">:</span> <span class="nv">en</span><span class="p-Indicator">}</span>
<span class="l-Scalar-Plain">requirements</span><span class="p-Indicator">:</span>
    <span class="l-Scalar-Plain">id</span><span class="p-Indicator">:</span> <span class="l-Scalar-Plain">\d+</span>
    <span class="l-Scalar-Plain">lang</span><span class="p-Indicator">:</span> <span class="l-Scalar-Plain">en|de</span>
</pre></div>
</div>
<p>Checking if the page is a <tt class="docutils literal"><span class="pre">POST</span></tt> request, and that <tt class="docutils literal"><span class="pre">{id}</span></tt> is numerical.</p>
<div class="highlight-yaml"><div class="highlight"><pre><span class="l-Scalar-Plain">Blog_EditSave</span><span class="p-Indicator">:</span>
<span class="l-Scalar-Plain">pattern</span><span class="p-Indicator">:</span> <span class="l-Scalar-Plain">/blog/edit/{id}</span>
<span class="l-Scalar-Plain">defaults</span><span class="p-Indicator">:</span> <span class="p-Indicator">{</span> <span class="nv">_controller</span><span class="p-Indicator">:</span> <span class="s">&quot;Application:Blog:edit&quot;</span><span class="p-Indicator">}</span>
<span class="l-Scalar-Plain">requirements</span><span class="p-Indicator">:</span>
    <span class="l-Scalar-Plain">_method</span><span class="p-Indicator">:</span> <span class="l-Scalar-Plain">POST</span>
    <span class="l-Scalar-Plain">id</span><span class="p-Indicator">:</span> <span class="l-Scalar-Plain">\d+</span>
</pre></div>
</div>
</div>
</div>


            
            </div>
        
        </div>
        
    </div>