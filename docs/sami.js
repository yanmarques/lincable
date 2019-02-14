
window.projectVersion = 'master';

(function(root) {

    var bhIndex = null;
    var rootPath = '';
    var treeHtml = '        <ul>                <li data-name="namespace:Lincable" class="opened">                    <div style="padding-left:0px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Lincable.html">Lincable</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="namespace:Lincable_Concerns" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Lincable/Concerns.html">Concerns</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Lincable_Concerns_BuildClassnames" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Lincable/Concerns/BuildClassnames.html">BuildClassnames</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:Lincable_Contracts" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Lincable/Contracts.html">Contracts</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="namespace:Lincable_Contracts_Compilers" >                    <div style="padding-left:36px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Lincable/Contracts/Compilers.html">Compilers</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Lincable_Contracts_Compilers_Compiler" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Lincable/Contracts/Compilers/Compiler.html">Compiler</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:Lincable_Contracts_Formatters" >                    <div style="padding-left:36px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Lincable/Contracts/Formatters.html">Formatters</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Lincable_Contracts_Formatters_Formatter" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Lincable/Contracts/Formatters/Formatter.html">Formatter</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:Lincable_Contracts_Parsers" >                    <div style="padding-left:36px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Lincable/Contracts/Parsers.html">Parsers</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Lincable_Contracts_Parsers_ParameterInterface" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Lincable/Contracts/Parsers/ParameterInterface.html">ParameterInterface</a>                    </div>                </li>                </ul></div>                </li>                </ul></div>                </li>                            <li data-name="namespace:Lincable_Eloquent" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Lincable/Eloquent.html">Eloquent</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="namespace:Lincable_Eloquent_Events" >                    <div style="padding-left:36px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Lincable/Eloquent/Events.html">Events</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Lincable_Eloquent_Events_Event" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Lincable/Eloquent/Events/Event.html">Event</a>                    </div>                </li>                            <li data-name="class:Lincable_Eloquent_Events_UploadFailure" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Lincable/Eloquent/Events/UploadFailure.html">UploadFailure</a>                    </div>                </li>                            <li data-name="class:Lincable_Eloquent_Events_UploadSuccess" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Lincable/Eloquent/Events/UploadSuccess.html">UploadSuccess</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:Lincable_Eloquent_Subscribers" >                    <div style="padding-left:36px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Lincable/Eloquent/Subscribers.html">Subscribers</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Lincable_Eloquent_Subscribers_UploadSubscriber" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Lincable/Eloquent/Subscribers/UploadSubscriber.html">UploadSubscriber</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="class:Lincable_Eloquent_Lincable" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Lincable/Eloquent/Lincable.html">Lincable</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:Lincable_Exceptions" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Lincable/Exceptions.html">Exceptions</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Lincable_Exceptions_ConfModelNotFoundException" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Lincable/Exceptions/ConfModelNotFoundException.html">ConfModelNotFoundException</a>                    </div>                </li>                            <li data-name="class:Lincable_Exceptions_ConflictFileUploadHttpException" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Lincable/Exceptions/ConflictFileUploadHttpException.html">ConflictFileUploadHttpException</a>                    </div>                </li>                            <li data-name="class:Lincable_Exceptions_LinkNotFoundException" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Lincable/Exceptions/LinkNotFoundException.html">LinkNotFoundException</a>                    </div>                </li>                            <li data-name="class:Lincable_Exceptions_NoModelConfException" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Lincable/Exceptions/NoModelConfException.html">NoModelConfException</a>                    </div>                </li>                            <li data-name="class:Lincable_Exceptions_NotDynamicOptionException" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Lincable/Exceptions/NotDynamicOptionException.html">NotDynamicOptionException</a>                    </div>                </li>                            <li data-name="class:Lincable_Exceptions_NotResolvableFileException" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Lincable/Exceptions/NotResolvableFileException.html">NotResolvableFileException</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:Lincable_Formatters" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Lincable/Formatters.html">Formatters</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Lincable_Formatters_DayFormatter" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Lincable/Formatters/DayFormatter.html">DayFormatter</a>                    </div>                </li>                            <li data-name="class:Lincable_Formatters_MonthFormatter" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Lincable/Formatters/MonthFormatter.html">MonthFormatter</a>                    </div>                </li>                            <li data-name="class:Lincable_Formatters_RandomFormatter" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Lincable/Formatters/RandomFormatter.html">RandomFormatter</a>                    </div>                </li>                            <li data-name="class:Lincable_Formatters_TimestampsFormatter" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Lincable/Formatters/TimestampsFormatter.html">TimestampsFormatter</a>                    </div>                </li>                            <li data-name="class:Lincable_Formatters_YearFormatter" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Lincable/Formatters/YearFormatter.html">YearFormatter</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:Lincable_Http" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Lincable/Http.html">Http</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="namespace:Lincable_Http_File" >                    <div style="padding-left:36px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Lincable/Http/File.html">File</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Lincable_Http_File_FileResolver" >                    <div style="padding-left:62px" class="hd leaf">                        <a href="Lincable/Http/File/FileResolver.html">FileResolver</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="class:Lincable_Http_FileRequest" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Lincable/Http/FileRequest.html">FileRequest</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:Lincable_Parsers" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Lincable/Parsers.html">Parsers</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Lincable_Parsers_ColonParser" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Lincable/Parsers/ColonParser.html">ColonParser</a>                    </div>                </li>                            <li data-name="class:Lincable_Parsers_Options" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Lincable/Parsers/Options.html">Options</a>                    </div>                </li>                            <li data-name="class:Lincable_Parsers_Parser" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Lincable/Parsers/Parser.html">Parser</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:Lincable_Providers" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Lincable/Providers.html">Providers</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Lincable_Providers_MediaManagerServiceProvider" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Lincable/Providers/MediaManagerServiceProvider.html">MediaManagerServiceProvider</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="class:Lincable_MediaManager" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="Lincable/MediaManager.html">MediaManager</a>                    </div>                </li>                            <li data-name="class:Lincable_UrlCompiler" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="Lincable/UrlCompiler.html">UrlCompiler</a>                    </div>                </li>                            <li data-name="class:Lincable_UrlConf" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="Lincable/UrlConf.html">UrlConf</a>                    </div>                </li>                            <li data-name="class:Lincable_UrlGenerator" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="Lincable/UrlGenerator.html">UrlGenerator</a>                    </div>                </li>                </ul></div>                </li>                </ul>';

    var searchTypeClasses = {
        'Namespace': 'label-default',
        'Class': 'label-info',
        'Interface': 'label-primary',
        'Trait': 'label-success',
        'Method': 'label-danger',
        '_': 'label-warning'
    };

    var searchIndex = [
                    
            {"type": "Namespace", "link": "Lincable.html", "name": "Lincable", "doc": "Namespace Lincable"},{"type": "Namespace", "link": "Lincable/Concerns.html", "name": "Lincable\\Concerns", "doc": "Namespace Lincable\\Concerns"},{"type": "Namespace", "link": "Lincable/Contracts.html", "name": "Lincable\\Contracts", "doc": "Namespace Lincable\\Contracts"},{"type": "Namespace", "link": "Lincable/Contracts/Compilers.html", "name": "Lincable\\Contracts\\Compilers", "doc": "Namespace Lincable\\Contracts\\Compilers"},{"type": "Namespace", "link": "Lincable/Contracts/Formatters.html", "name": "Lincable\\Contracts\\Formatters", "doc": "Namespace Lincable\\Contracts\\Formatters"},{"type": "Namespace", "link": "Lincable/Contracts/Parsers.html", "name": "Lincable\\Contracts\\Parsers", "doc": "Namespace Lincable\\Contracts\\Parsers"},{"type": "Namespace", "link": "Lincable/Eloquent.html", "name": "Lincable\\Eloquent", "doc": "Namespace Lincable\\Eloquent"},{"type": "Namespace", "link": "Lincable/Eloquent/Events.html", "name": "Lincable\\Eloquent\\Events", "doc": "Namespace Lincable\\Eloquent\\Events"},{"type": "Namespace", "link": "Lincable/Eloquent/Subscribers.html", "name": "Lincable\\Eloquent\\Subscribers", "doc": "Namespace Lincable\\Eloquent\\Subscribers"},{"type": "Namespace", "link": "Lincable/Exceptions.html", "name": "Lincable\\Exceptions", "doc": "Namespace Lincable\\Exceptions"},{"type": "Namespace", "link": "Lincable/Formatters.html", "name": "Lincable\\Formatters", "doc": "Namespace Lincable\\Formatters"},{"type": "Namespace", "link": "Lincable/Http.html", "name": "Lincable\\Http", "doc": "Namespace Lincable\\Http"},{"type": "Namespace", "link": "Lincable/Http/File.html", "name": "Lincable\\Http\\File", "doc": "Namespace Lincable\\Http\\File"},{"type": "Namespace", "link": "Lincable/Parsers.html", "name": "Lincable\\Parsers", "doc": "Namespace Lincable\\Parsers"},{"type": "Namespace", "link": "Lincable/Providers.html", "name": "Lincable\\Providers", "doc": "Namespace Lincable\\Providers"},
            {"type": "Interface", "fromName": "Lincable\\Contracts\\Compilers", "fromLink": "Lincable/Contracts/Compilers.html", "link": "Lincable/Contracts/Compilers/Compiler.html", "name": "Lincable\\Contracts\\Compilers\\Compiler", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Lincable\\Contracts\\Compilers\\Compiler", "fromLink": "Lincable/Contracts/Compilers/Compiler.html", "link": "Lincable/Contracts/Compilers/Compiler.html#method_compile", "name": "Lincable\\Contracts\\Compilers\\Compiler::compile", "doc": "&quot;Compile a given url through the parser.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Contracts\\Compilers\\Compiler", "fromLink": "Lincable/Contracts/Compilers/Compiler.html", "link": "Lincable/Contracts/Compilers/Compiler.html#method_parseDynamics", "name": "Lincable\\Contracts\\Compilers\\Compiler::parseDynamics", "doc": "&quot;Get all dynamic parameters on url based on parser.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Contracts\\Compilers\\Compiler", "fromLink": "Lincable/Contracts/Compilers/Compiler.html", "link": "Lincable/Contracts/Compilers/Compiler.html#method_hasDynamics", "name": "Lincable\\Contracts\\Compilers\\Compiler::hasDynamics", "doc": "&quot;Determine wheter the url has dynamic parameters.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Contracts\\Compilers\\Compiler", "fromLink": "Lincable/Contracts/Compilers/Compiler.html", "link": "Lincable/Contracts/Compilers/Compiler.html#method_parseUrlFragments", "name": "Lincable\\Contracts\\Compilers\\Compiler::parseUrlFragments", "doc": "&quot;Return all url fragments.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Contracts\\Compilers\\Compiler", "fromLink": "Lincable/Contracts/Compilers/Compiler.html", "link": "Lincable/Contracts/Compilers/Compiler.html#method_buildUrlFragments", "name": "Lincable\\Contracts\\Compilers\\Compiler::buildUrlFragments", "doc": "&quot;Build an url from array fragments.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Contracts\\Compilers\\Compiler", "fromLink": "Lincable/Contracts/Compilers/Compiler.html", "link": "Lincable/Contracts/Compilers/Compiler.html#method_setParser", "name": "Lincable\\Contracts\\Compilers\\Compiler::setParser", "doc": "&quot;Set the parser used on compiler.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Contracts\\Compilers\\Compiler", "fromLink": "Lincable/Contracts/Compilers/Compiler.html", "link": "Lincable/Contracts/Compilers/Compiler.html#method_getParser", "name": "Lincable\\Contracts\\Compilers\\Compiler::getParser", "doc": "&quot;Get the current parser used on compiler.&quot;"},
            
            {"type": "Interface", "fromName": "Lincable\\Contracts\\Formatters", "fromLink": "Lincable/Contracts/Formatters.html", "link": "Lincable/Contracts/Formatters/Formatter.html", "name": "Lincable\\Contracts\\Formatters\\Formatter", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Lincable\\Contracts\\Formatters\\Formatter", "fromLink": "Lincable/Contracts/Formatters/Formatter.html", "link": "Lincable/Contracts/Formatters/Formatter.html#method_format", "name": "Lincable\\Contracts\\Formatters\\Formatter::format", "doc": "&quot;Return a formatted string option.&quot;"},
            
            {"type": "Interface", "fromName": "Lincable\\Contracts\\Parsers", "fromLink": "Lincable/Contracts/Parsers.html", "link": "Lincable/Contracts/Parsers/ParameterInterface.html", "name": "Lincable\\Contracts\\Parsers\\ParameterInterface", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Lincable\\Contracts\\Parsers\\ParameterInterface", "fromLink": "Lincable/Contracts/Parsers/ParameterInterface.html", "link": "Lincable/Contracts/Parsers/ParameterInterface.html#method___construct", "name": "Lincable\\Contracts\\Parsers\\ParameterInterface::__construct", "doc": "&quot;Create a new parameter instance.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Contracts\\Parsers\\ParameterInterface", "fromLink": "Lincable/Contracts/Parsers/ParameterInterface.html", "link": "Lincable/Contracts/Parsers/ParameterInterface.html#method_getValue", "name": "Lincable\\Contracts\\Parsers\\ParameterInterface::getValue", "doc": "&quot;Get the parameter value.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Contracts\\Parsers\\ParameterInterface", "fromLink": "Lincable/Contracts/Parsers/ParameterInterface.html", "link": "Lincable/Contracts/Parsers/ParameterInterface.html#method_getParams", "name": "Lincable\\Contracts\\Parsers\\ParameterInterface::getParams", "doc": "&quot;Get all parameters.&quot;"},
            
            
            {"type": "Trait", "fromName": "Lincable\\Concerns", "fromLink": "Lincable/Concerns.html", "link": "Lincable/Concerns/BuildClassnames.html", "name": "Lincable\\Concerns\\BuildClassnames", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Lincable\\Concerns\\BuildClassnames", "fromLink": "Lincable/Concerns/BuildClassnames.html", "link": "Lincable/Concerns/BuildClassnames.html#method_nameFromClass", "name": "Lincable\\Concerns\\BuildClassnames::nameFromClass", "doc": "&quot;Return the basename of class do camel case removing\nunecessary suffixes.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Concerns\\BuildClassnames", "fromLink": "Lincable/Concerns/BuildClassnames.html", "link": "Lincable/Concerns/BuildClassnames.html#method_buildNamespace", "name": "Lincable\\Concerns\\BuildClassnames::buildNamespace", "doc": "&quot;Return the namespace from array of classes.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Concerns\\BuildClassnames", "fromLink": "Lincable/Concerns/BuildClassnames.html", "link": "Lincable/Concerns/BuildClassnames.html#method_classToCamelCase", "name": "Lincable\\Concerns\\BuildClassnames::classToCamelCase", "doc": "&quot;Return the class basename to camel case.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Concerns\\BuildClassnames", "fromLink": "Lincable/Concerns/BuildClassnames.html", "link": "Lincable/Concerns/BuildClassnames.html#method_classToSnakeCase", "name": "Lincable\\Concerns\\BuildClassnames::classToSnakeCase", "doc": "&quot;Return the class basename to snake case.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Concerns\\BuildClassnames", "fromLink": "Lincable/Concerns/BuildClassnames.html", "link": "Lincable/Concerns/BuildClassnames.html#method_removeBackslash", "name": "Lincable\\Concerns\\BuildClassnames::removeBackslash", "doc": "&quot;Remove the backslash on start of class.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Concerns\\BuildClassnames", "fromLink": "Lincable/Concerns/BuildClassnames.html", "link": "Lincable/Concerns/BuildClassnames.html#method_convertCase", "name": "Lincable\\Concerns\\BuildClassnames::convertCase", "doc": "&quot;Convert the class basenmae.&quot;"},
            
            {"type": "Class", "fromName": "Lincable\\Contracts\\Compilers", "fromLink": "Lincable/Contracts/Compilers.html", "link": "Lincable/Contracts/Compilers/Compiler.html", "name": "Lincable\\Contracts\\Compilers\\Compiler", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Lincable\\Contracts\\Compilers\\Compiler", "fromLink": "Lincable/Contracts/Compilers/Compiler.html", "link": "Lincable/Contracts/Compilers/Compiler.html#method_compile", "name": "Lincable\\Contracts\\Compilers\\Compiler::compile", "doc": "&quot;Compile a given url through the parser.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Contracts\\Compilers\\Compiler", "fromLink": "Lincable/Contracts/Compilers/Compiler.html", "link": "Lincable/Contracts/Compilers/Compiler.html#method_parseDynamics", "name": "Lincable\\Contracts\\Compilers\\Compiler::parseDynamics", "doc": "&quot;Get all dynamic parameters on url based on parser.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Contracts\\Compilers\\Compiler", "fromLink": "Lincable/Contracts/Compilers/Compiler.html", "link": "Lincable/Contracts/Compilers/Compiler.html#method_hasDynamics", "name": "Lincable\\Contracts\\Compilers\\Compiler::hasDynamics", "doc": "&quot;Determine wheter the url has dynamic parameters.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Contracts\\Compilers\\Compiler", "fromLink": "Lincable/Contracts/Compilers/Compiler.html", "link": "Lincable/Contracts/Compilers/Compiler.html#method_parseUrlFragments", "name": "Lincable\\Contracts\\Compilers\\Compiler::parseUrlFragments", "doc": "&quot;Return all url fragments.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Contracts\\Compilers\\Compiler", "fromLink": "Lincable/Contracts/Compilers/Compiler.html", "link": "Lincable/Contracts/Compilers/Compiler.html#method_buildUrlFragments", "name": "Lincable\\Contracts\\Compilers\\Compiler::buildUrlFragments", "doc": "&quot;Build an url from array fragments.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Contracts\\Compilers\\Compiler", "fromLink": "Lincable/Contracts/Compilers/Compiler.html", "link": "Lincable/Contracts/Compilers/Compiler.html#method_setParser", "name": "Lincable\\Contracts\\Compilers\\Compiler::setParser", "doc": "&quot;Set the parser used on compiler.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Contracts\\Compilers\\Compiler", "fromLink": "Lincable/Contracts/Compilers/Compiler.html", "link": "Lincable/Contracts/Compilers/Compiler.html#method_getParser", "name": "Lincable\\Contracts\\Compilers\\Compiler::getParser", "doc": "&quot;Get the current parser used on compiler.&quot;"},
            
            {"type": "Class", "fromName": "Lincable\\Contracts\\Formatters", "fromLink": "Lincable/Contracts/Formatters.html", "link": "Lincable/Contracts/Formatters/Formatter.html", "name": "Lincable\\Contracts\\Formatters\\Formatter", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Lincable\\Contracts\\Formatters\\Formatter", "fromLink": "Lincable/Contracts/Formatters/Formatter.html", "link": "Lincable/Contracts/Formatters/Formatter.html#method_format", "name": "Lincable\\Contracts\\Formatters\\Formatter::format", "doc": "&quot;Return a formatted string option.&quot;"},
            
            {"type": "Class", "fromName": "Lincable\\Contracts\\Parsers", "fromLink": "Lincable/Contracts/Parsers.html", "link": "Lincable/Contracts/Parsers/ParameterInterface.html", "name": "Lincable\\Contracts\\Parsers\\ParameterInterface", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Lincable\\Contracts\\Parsers\\ParameterInterface", "fromLink": "Lincable/Contracts/Parsers/ParameterInterface.html", "link": "Lincable/Contracts/Parsers/ParameterInterface.html#method___construct", "name": "Lincable\\Contracts\\Parsers\\ParameterInterface::__construct", "doc": "&quot;Create a new parameter instance.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Contracts\\Parsers\\ParameterInterface", "fromLink": "Lincable/Contracts/Parsers/ParameterInterface.html", "link": "Lincable/Contracts/Parsers/ParameterInterface.html#method_getValue", "name": "Lincable\\Contracts\\Parsers\\ParameterInterface::getValue", "doc": "&quot;Get the parameter value.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Contracts\\Parsers\\ParameterInterface", "fromLink": "Lincable/Contracts/Parsers/ParameterInterface.html", "link": "Lincable/Contracts/Parsers/ParameterInterface.html#method_getParams", "name": "Lincable\\Contracts\\Parsers\\ParameterInterface::getParams", "doc": "&quot;Get all parameters.&quot;"},
            
            {"type": "Class", "fromName": "Lincable\\Eloquent\\Events", "fromLink": "Lincable/Eloquent/Events.html", "link": "Lincable/Eloquent/Events/Event.html", "name": "Lincable\\Eloquent\\Events\\Event", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Lincable\\Eloquent\\Events\\Event", "fromLink": "Lincable/Eloquent/Events/Event.html", "link": "Lincable/Eloquent/Events/Event.html#method___construct", "name": "Lincable\\Eloquent\\Events\\Event::__construct", "doc": "&quot;Construtor da classe.&quot;"},
            
            {"type": "Class", "fromName": "Lincable\\Eloquent\\Events", "fromLink": "Lincable/Eloquent/Events.html", "link": "Lincable/Eloquent/Events/UploadFailure.html", "name": "Lincable\\Eloquent\\Events\\UploadFailure", "doc": "&quot;&quot;"},
                    
            {"type": "Class", "fromName": "Lincable\\Eloquent\\Events", "fromLink": "Lincable/Eloquent/Events.html", "link": "Lincable/Eloquent/Events/UploadSuccess.html", "name": "Lincable\\Eloquent\\Events\\UploadSuccess", "doc": "&quot;&quot;"},
                    
            {"type": "Trait", "fromName": "Lincable\\Eloquent", "fromLink": "Lincable/Eloquent.html", "link": "Lincable/Eloquent/Lincable.html", "name": "Lincable\\Eloquent\\Lincable", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Lincable\\Eloquent\\Lincable", "fromLink": "Lincable/Eloquent/Lincable.html", "link": "Lincable/Eloquent/Lincable.html#method_bootLincable", "name": "Lincable\\Eloquent\\Lincable::bootLincable", "doc": "&quot;Boot the trait with model.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Eloquent\\Lincable", "fromLink": "Lincable/Eloquent/Lincable.html", "link": "Lincable/Eloquent/Lincable.html#method_addLincableFields", "name": "Lincable\\Eloquent\\Lincable::addLincableFields", "doc": "&quot;Add the lincable fields to model fillables.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Eloquent\\Lincable", "fromLink": "Lincable/Eloquent/Lincable.html", "link": "Lincable/Eloquent/Lincable.html#method_link", "name": "Lincable\\Eloquent\\Lincable::link", "doc": "&quot;Link the model to a file.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Eloquent\\Lincable", "fromLink": "Lincable/Eloquent/Lincable.html", "link": "Lincable/Eloquent/Lincable.html#method_withMedia", "name": "Lincable\\Eloquent\\Lincable::withMedia", "doc": "&quot;Execute a container callable with the file as argument.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Eloquent\\Lincable", "fromLink": "Lincable/Eloquent/Lincable.html", "link": "Lincable/Eloquent/Lincable.html#method_getUrlField", "name": "Lincable\\Eloquent\\Lincable::getUrlField", "doc": "&quot;Return the url field to link the model.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Eloquent\\Lincable", "fromLink": "Lincable/Eloquent/Lincable.html", "link": "Lincable/Eloquent/Lincable.html#method_throwUploadFailureException", "name": "Lincable\\Eloquent\\Lincable::throwUploadFailureException", "doc": "&quot;Throw a HTTP exception indicating that file could not be uploaded.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Eloquent\\Lincable", "fromLink": "Lincable/Eloquent/Lincable.html", "link": "Lincable/Eloquent/Lincable.html#method_handleUpload", "name": "Lincable\\Eloquent\\Lincable::handleUpload", "doc": "&quot;Handle the file upload for the model.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Eloquent\\Lincable", "fromLink": "Lincable/Eloquent/Lincable.html", "link": "Lincable/Eloquent/Lincable.html#method_retrieveLink", "name": "Lincable\\Eloquent\\Lincable::retrieveLink", "doc": "&quot;Return the link from model.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Eloquent\\Lincable", "fromLink": "Lincable/Eloquent/Lincable.html", "link": "Lincable/Eloquent/Lincable.html#method_getUrl", "name": "Lincable\\Eloquent\\Lincable::getUrl", "doc": "&quot;Return the url from the link storage.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Eloquent\\Lincable", "fromLink": "Lincable/Eloquent/Lincable.html", "link": "Lincable/Eloquent/Lincable.html#method_urlFromStorage", "name": "Lincable\\Eloquent\\Lincable::urlFromStorage", "doc": "&quot;Return the base url from the disk storage.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Eloquent\\Lincable", "fromLink": "Lincable/Eloquent/Lincable.html", "link": "Lincable/Eloquent/Lincable.html#method_getDriver", "name": "Lincable\\Eloquent\\Lincable::getDriver", "doc": "&quot;Return the filesystem driver.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Eloquent\\Lincable", "fromLink": "Lincable/Eloquent/Lincable.html", "link": "Lincable/Eloquent/Lincable.html#method_createTemporaryFile", "name": "Lincable\\Eloquent\\Lincable::createTemporaryFile", "doc": "&quot;Create a temporary file from the model link.&quot;"},
            
            {"type": "Class", "fromName": "Lincable\\Eloquent\\Subscribers", "fromLink": "Lincable/Eloquent/Subscribers.html", "link": "Lincable/Eloquent/Subscribers/UploadSubscriber.html", "name": "Lincable\\Eloquent\\Subscribers\\UploadSubscriber", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Lincable\\Eloquent\\Subscribers\\UploadSubscriber", "fromLink": "Lincable/Eloquent/Subscribers/UploadSubscriber.html", "link": "Lincable/Eloquent/Subscribers/UploadSubscriber.html#method_onSuccess", "name": "Lincable\\Eloquent\\Subscribers\\UploadSubscriber::onSuccess", "doc": "&quot;Listen when the upload has been executed with success.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Eloquent\\Subscribers\\UploadSubscriber", "fromLink": "Lincable/Eloquent/Subscribers/UploadSubscriber.html", "link": "Lincable/Eloquent/Subscribers/UploadSubscriber.html#method_onFailure", "name": "Lincable\\Eloquent\\Subscribers\\UploadSubscriber::onFailure", "doc": "&quot;Listen when the upload has failed.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Eloquent\\Subscribers\\UploadSubscriber", "fromLink": "Lincable/Eloquent/Subscribers/UploadSubscriber.html", "link": "Lincable/Eloquent/Subscribers/UploadSubscriber.html#method_subscribe", "name": "Lincable\\Eloquent\\Subscribers\\UploadSubscriber::subscribe", "doc": "&quot;Register the listeners for the subscriber.&quot;"},
            
            {"type": "Class", "fromName": "Lincable\\Exceptions", "fromLink": "Lincable/Exceptions.html", "link": "Lincable/Exceptions/ConfModelNotFoundException.html", "name": "Lincable\\Exceptions\\ConfModelNotFoundException", "doc": "&quot;&quot;"},
                    
            {"type": "Class", "fromName": "Lincable\\Exceptions", "fromLink": "Lincable/Exceptions.html", "link": "Lincable/Exceptions/ConflictFileUploadHttpException.html", "name": "Lincable\\Exceptions\\ConflictFileUploadHttpException", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Lincable\\Exceptions\\ConflictFileUploadHttpException", "fromLink": "Lincable/Exceptions/ConflictFileUploadHttpException.html", "link": "Lincable/Exceptions/ConflictFileUploadHttpException.html#method___construct", "name": "Lincable\\Exceptions\\ConflictFileUploadHttpException::__construct", "doc": "&quot;Create a new exception instance.&quot;"},
            
            {"type": "Class", "fromName": "Lincable\\Exceptions", "fromLink": "Lincable/Exceptions.html", "link": "Lincable/Exceptions/LinkNotFoundException.html", "name": "Lincable\\Exceptions\\LinkNotFoundException", "doc": "&quot;&quot;"},
                    
            {"type": "Class", "fromName": "Lincable\\Exceptions", "fromLink": "Lincable/Exceptions.html", "link": "Lincable/Exceptions/NoModelConfException.html", "name": "Lincable\\Exceptions\\NoModelConfException", "doc": "&quot;&quot;"},
                    
            {"type": "Class", "fromName": "Lincable\\Exceptions", "fromLink": "Lincable/Exceptions.html", "link": "Lincable/Exceptions/NotDynamicOptionException.html", "name": "Lincable\\Exceptions\\NotDynamicOptionException", "doc": "&quot;&quot;"},
                    
            {"type": "Class", "fromName": "Lincable\\Exceptions", "fromLink": "Lincable/Exceptions.html", "link": "Lincable/Exceptions/NotResolvableFileException.html", "name": "Lincable\\Exceptions\\NotResolvableFileException", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Lincable\\Exceptions\\NotResolvableFileException", "fromLink": "Lincable/Exceptions/NotResolvableFileException.html", "link": "Lincable/Exceptions/NotResolvableFileException.html#method___construct", "name": "Lincable\\Exceptions\\NotResolvableFileException::__construct", "doc": "&quot;Create a new exception class instance.&quot;"},
            
            {"type": "Class", "fromName": "Lincable\\Formatters", "fromLink": "Lincable/Formatters.html", "link": "Lincable/Formatters/DayFormatter.html", "name": "Lincable\\Formatters\\DayFormatter", "doc": "&quot;This class formats the current day.&quot;"},
                                                        {"type": "Method", "fromName": "Lincable\\Formatters\\DayFormatter", "fromLink": "Lincable/Formatters/DayFormatter.html", "link": "Lincable/Formatters/DayFormatter.html#method_format", "name": "Lincable\\Formatters\\DayFormatter::format", "doc": "&quot;Return a formatted string option.&quot;"},
            
            {"type": "Class", "fromName": "Lincable\\Formatters", "fromLink": "Lincable/Formatters.html", "link": "Lincable/Formatters/MonthFormatter.html", "name": "Lincable\\Formatters\\MonthFormatter", "doc": "&quot;This class formats the current month.&quot;"},
                                                        {"type": "Method", "fromName": "Lincable\\Formatters\\MonthFormatter", "fromLink": "Lincable/Formatters/MonthFormatter.html", "link": "Lincable/Formatters/MonthFormatter.html#method_format", "name": "Lincable\\Formatters\\MonthFormatter::format", "doc": "&quot;Return a formatted string option.&quot;"},
            
            {"type": "Class", "fromName": "Lincable\\Formatters", "fromLink": "Lincable/Formatters.html", "link": "Lincable/Formatters/RandomFormatter.html", "name": "Lincable\\Formatters\\RandomFormatter", "doc": "&quot;This class formats a random string.&quot;"},
                                                        {"type": "Method", "fromName": "Lincable\\Formatters\\RandomFormatter", "fromLink": "Lincable/Formatters/RandomFormatter.html", "link": "Lincable/Formatters/RandomFormatter.html#method___construct", "name": "Lincable\\Formatters\\RandomFormatter::__construct", "doc": "&quot;Create a new class instance.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Formatters\\RandomFormatter", "fromLink": "Lincable/Formatters/RandomFormatter.html", "link": "Lincable/Formatters/RandomFormatter.html#method_format", "name": "Lincable\\Formatters\\RandomFormatter::format", "doc": "&quot;Return a formatted string option.&quot;"},
            
            {"type": "Class", "fromName": "Lincable\\Formatters", "fromLink": "Lincable/Formatters.html", "link": "Lincable/Formatters/TimestampsFormatter.html", "name": "Lincable\\Formatters\\TimestampsFormatter", "doc": "&quot;This class formats a hash of the current timestamps.&quot;"},
                                                        {"type": "Method", "fromName": "Lincable\\Formatters\\TimestampsFormatter", "fromLink": "Lincable/Formatters/TimestampsFormatter.html", "link": "Lincable/Formatters/TimestampsFormatter.html#method_format", "name": "Lincable\\Formatters\\TimestampsFormatter::format", "doc": "&quot;Return a formatted string option.&quot;"},
            
            {"type": "Class", "fromName": "Lincable\\Formatters", "fromLink": "Lincable/Formatters.html", "link": "Lincable/Formatters/YearFormatter.html", "name": "Lincable\\Formatters\\YearFormatter", "doc": "&quot;This class formats the current year.&quot;"},
                                                        {"type": "Method", "fromName": "Lincable\\Formatters\\YearFormatter", "fromLink": "Lincable/Formatters/YearFormatter.html", "link": "Lincable/Formatters/YearFormatter.html#method_format", "name": "Lincable\\Formatters\\YearFormatter::format", "doc": "&quot;Return a formatted string option.&quot;"},
            
            {"type": "Class", "fromName": "Lincable\\Http", "fromLink": "Lincable/Http.html", "link": "Lincable/Http/FileRequest.html", "name": "Lincable\\Http\\FileRequest", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Lincable\\Http\\FileRequest", "fromLink": "Lincable/Http/FileRequest.html", "link": "Lincable/Http/FileRequest.html#method_rules", "name": "Lincable\\Http\\FileRequest::rules", "doc": "&quot;Rules to validate the file on request.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Http\\FileRequest", "fromLink": "Lincable/Http/FileRequest.html", "link": "Lincable/Http/FileRequest.html#method_boot", "name": "Lincable\\Http\\FileRequest::boot", "doc": "&quot;Boot the instance with the request.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Http\\FileRequest", "fromLink": "Lincable/Http/FileRequest.html", "link": "Lincable/Http/FileRequest.html#method_isBooted", "name": "Lincable\\Http\\FileRequest::isBooted", "doc": "&quot;Return wheter the file request is booted.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Http\\FileRequest", "fromLink": "Lincable/Http/FileRequest.html", "link": "Lincable/Http/FileRequest.html#method_getFile", "name": "Lincable\\Http\\FileRequest::getFile", "doc": "&quot;Return the file on request.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Http\\FileRequest", "fromLink": "Lincable/Http/FileRequest.html", "link": "Lincable/Http/FileRequest.html#method_getRequest", "name": "Lincable\\Http\\FileRequest::getRequest", "doc": "&quot;Return the current request instance.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Http\\FileRequest", "fromLink": "Lincable/Http/FileRequest.html", "link": "Lincable/Http/FileRequest.html#method_getParameter", "name": "Lincable\\Http\\FileRequest::getParameter", "doc": "&quot;Return the parameter name.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Http\\FileRequest", "fromLink": "Lincable/Http/FileRequest.html", "link": "Lincable/Http/FileRequest.html#method_setParameter", "name": "Lincable\\Http\\FileRequest::setParameter", "doc": "&quot;Set the parameter name.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Http\\FileRequest", "fromLink": "Lincable/Http/FileRequest.html", "link": "Lincable/Http/FileRequest.html#method_as", "name": "Lincable\\Http\\FileRequest::as", "doc": "&quot;Shortcut for @method setParameter.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Http\\FileRequest", "fromLink": "Lincable/Http/FileRequest.html", "link": "Lincable/Http/FileRequest.html#method_prepareFile", "name": "Lincable\\Http\\FileRequest::prepareFile", "doc": "&quot;Prepared the file to send.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Http\\FileRequest", "fromLink": "Lincable/Http/FileRequest.html", "link": "Lincable/Http/FileRequest.html#method_validate", "name": "Lincable\\Http\\FileRequest::validate", "doc": "&quot;Validate the file with the defined rules.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Http\\FileRequest", "fromLink": "Lincable/Http/FileRequest.html", "link": "Lincable/Http/FileRequest.html#method_retrieveParameter", "name": "Lincable\\Http\\FileRequest::retrieveParameter", "doc": "&quot;Return the parameter name from class name.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Http\\FileRequest", "fromLink": "Lincable/Http/FileRequest.html", "link": "Lincable/Http/FileRequest.html#method_parseValidationRules", "name": "Lincable\\Http\\FileRequest::parseValidationRules", "doc": "&quot;Get the rules for the file validation.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Http\\FileRequest", "fromLink": "Lincable/Http/FileRequest.html", "link": "Lincable/Http/FileRequest.html#method_moveFileToTempDirectory", "name": "Lincable\\Http\\FileRequest::moveFileToTempDirectory", "doc": "&quot;Move the file to a temporary destination.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Http\\FileRequest", "fromLink": "Lincable/Http/FileRequest.html", "link": "Lincable/Http/FileRequest.html#method_executeFileEvents", "name": "Lincable\\Http\\FileRequest::executeFileEvents", "doc": "&quot;Execute some generic event methods on class if available.&quot;"},
            
            {"type": "Class", "fromName": "Lincable\\Http\\File", "fromLink": "Lincable/Http/File.html", "link": "Lincable/Http/File/FileResolver.html", "name": "Lincable\\Http\\File\\FileResolver", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Lincable\\Http\\File\\FileResolver", "fromLink": "Lincable/Http/File/FileResolver.html", "link": "Lincable/Http/File/FileResolver.html#method_resolve", "name": "Lincable\\Http\\File\\FileResolver::resolve", "doc": "&quot;Resolve the file object to a symfony file, handling the\nfile request operations.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Http\\File\\FileResolver", "fromLink": "Lincable/Http/File/FileResolver.html", "link": "Lincable/Http/File/FileResolver.html#method_toIlluminateFile", "name": "Lincable\\Http\\File\\FileResolver::toIlluminateFile", "doc": "&quot;Convert a symfony file to illuminate file.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Http\\File\\FileResolver", "fromLink": "Lincable/Http/File/FileResolver.html", "link": "Lincable/Http/File/FileResolver.html#method_resolveFileRequest", "name": "Lincable\\Http\\File\\FileResolver::resolveFileRequest", "doc": "&quot;Handle a file request and resolve to a file.&quot;"},
            
            {"type": "Class", "fromName": "Lincable", "fromLink": "Lincable.html", "link": "Lincable/MediaManager.html", "name": "Lincable\\MediaManager", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Lincable\\MediaManager", "fromLink": "Lincable/MediaManager.html", "link": "Lincable/MediaManager.html#method___construct", "name": "Lincable\\MediaManager::__construct", "doc": "&quot;Create a new class instance.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\MediaManager", "fromLink": "Lincable/MediaManager.html", "link": "Lincable/MediaManager.html#method_setCompiler", "name": "Lincable\\MediaManager::setCompiler", "doc": "&quot;Set the media url compiler.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\MediaManager", "fromLink": "Lincable/MediaManager.html", "link": "Lincable/MediaManager.html#method_getCompiler", "name": "Lincable\\MediaManager::getCompiler", "doc": "&quot;Return the compiler class.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\MediaManager", "fromLink": "Lincable/MediaManager.html", "link": "Lincable/MediaManager.html#method_getDisk", "name": "Lincable\\MediaManager::getDisk", "doc": "&quot;Return the disk storage.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\MediaManager", "fromLink": "Lincable/MediaManager.html", "link": "Lincable/MediaManager.html#method_getUrlConf", "name": "Lincable\\MediaManager::getUrlConf", "doc": "&quot;Return the model url configuration.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\MediaManager", "fromLink": "Lincable/MediaManager.html", "link": "Lincable/MediaManager.html#method_setDisk", "name": "Lincable\\MediaManager::setDisk", "doc": "&quot;Set the new disk to use.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\MediaManager", "fromLink": "Lincable/MediaManager.html", "link": "Lincable/MediaManager.html#method_setRoot", "name": "Lincable\\MediaManager::setRoot", "doc": "&quot;Set a root path for the urls.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\MediaManager", "fromLink": "Lincable/MediaManager.html", "link": "Lincable/MediaManager.html#method_addParser", "name": "Lincable\\MediaManager::addParser", "doc": "&quot;Add a new parser to the manager.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\MediaManager", "fromLink": "Lincable/MediaManager.html", "link": "Lincable/MediaManager.html#method_buildUrlGenerator", "name": "Lincable\\MediaManager::buildUrlGenerator", "doc": "&quot;Return a url generator instance with the manager configuration.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\MediaManager", "fromLink": "Lincable/MediaManager.html", "link": "Lincable/MediaManager.html#method_readConfig", "name": "Lincable\\MediaManager::readConfig", "doc": "&quot;Read the configuration from container.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\MediaManager", "fromLink": "Lincable/MediaManager.html", "link": "Lincable/MediaManager.html#method_getConfig", "name": "Lincable\\MediaManager::getConfig", "doc": "&quot;Return the configuration value.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\MediaManager", "fromLink": "Lincable/MediaManager.html", "link": "Lincable/MediaManager.html#method_createUrlConfWithRoot", "name": "Lincable\\MediaManager::createUrlConfWithRoot", "doc": "&quot;Create a new url conf with a root url.&quot;"},
            
            {"type": "Class", "fromName": "Lincable\\Parsers", "fromLink": "Lincable/Parsers.html", "link": "Lincable/Parsers/ColonParser.html", "name": "Lincable\\Parsers\\ColonParser", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Lincable\\Parsers\\ColonParser", "fromLink": "Lincable/Parsers/ColonParser.html", "link": "Lincable/Parsers/ColonParser.html#method___construct", "name": "Lincable\\Parsers\\ColonParser::__construct", "doc": "&quot;Create a new class instance.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Parsers\\ColonParser", "fromLink": "Lincable/Parsers/ColonParser.html", "link": "Lincable/Parsers/ColonParser.html#method_parseMatches", "name": "Lincable\\Parsers\\ColonParser::parseMatches", "doc": "&quot;Return the formatter call for the matches on parse.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Parsers\\ColonParser", "fromLink": "Lincable/Parsers/ColonParser.html", "link": "Lincable/Parsers/ColonParser.html#method_getDynamicPattern", "name": "Lincable\\Parsers\\ColonParser::getDynamicPattern", "doc": "&quot;Return the dynamic regex pattern.&quot;"},
            
            {"type": "Class", "fromName": "Lincable\\Parsers", "fromLink": "Lincable/Parsers.html", "link": "Lincable/Parsers/Options.html", "name": "Lincable\\Parsers\\Options", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Lincable\\Parsers\\Options", "fromLink": "Lincable/Parsers/Options.html", "link": "Lincable/Parsers/Options.html#method___construct", "name": "Lincable\\Parsers\\Options::__construct", "doc": "&quot;Create a new parameter instance.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Parsers\\Options", "fromLink": "Lincable/Parsers/Options.html", "link": "Lincable/Parsers/Options.html#method_getValue", "name": "Lincable\\Parsers\\Options::getValue", "doc": "&quot;Get the parameter value.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Parsers\\Options", "fromLink": "Lincable/Parsers/Options.html", "link": "Lincable/Parsers/Options.html#method_getParams", "name": "Lincable\\Parsers\\Options::getParams", "doc": "&quot;Get all parameters.&quot;"},
            
            {"type": "Class", "fromName": "Lincable\\Parsers", "fromLink": "Lincable/Parsers.html", "link": "Lincable/Parsers/Parser.html", "name": "Lincable\\Parsers\\Parser", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Lincable\\Parsers\\Parser", "fromLink": "Lincable/Parsers/Parser.html", "link": "Lincable/Parsers/Parser.html#method_parseMatches", "name": "Lincable\\Parsers\\Parser::parseMatches", "doc": "&quot;Return the formatter call for the matches on parse.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Parsers\\Parser", "fromLink": "Lincable/Parsers/Parser.html", "link": "Lincable/Parsers/Parser.html#method_getDynamicPattern", "name": "Lincable\\Parsers\\Parser::getDynamicPattern", "doc": "&quot;Return the dynamic regex pattern.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Parsers\\Parser", "fromLink": "Lincable/Parsers/Parser.html", "link": "Lincable/Parsers/Parser.html#method_boot", "name": "Lincable\\Parsers\\Parser::boot", "doc": "&quot;Boot the parser with the container executing initial tasks.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Parsers\\Parser", "fromLink": "Lincable/Parsers/Parser.html", "link": "Lincable/Parsers/Parser.html#method_addFormatter", "name": "Lincable\\Parsers\\Parser::addFormatter", "doc": "&quot;Push a new formatter to collection.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Parsers\\Parser", "fromLink": "Lincable/Parsers/Parser.html", "link": "Lincable/Parsers/Parser.html#method_parse", "name": "Lincable\\Parsers\\Parser::parse", "doc": "&quot;Parse an option through formatters.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Parsers\\Parser", "fromLink": "Lincable/Parsers/Parser.html", "link": "Lincable/Parsers/Parser.html#method_addFormatters", "name": "Lincable\\Parsers\\Parser::addFormatters", "doc": "&quot;Append a list of formatters.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Parsers\\Parser", "fromLink": "Lincable/Parsers/Parser.html", "link": "Lincable/Parsers/Parser.html#method_setFormatters", "name": "Lincable\\Parsers\\Parser::setFormatters", "doc": "&quot;Set the list with the new formatters.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Parsers\\Parser", "fromLink": "Lincable/Parsers/Parser.html", "link": "Lincable/Parsers/Parser.html#method_getFormatters", "name": "Lincable\\Parsers\\Parser::getFormatters", "doc": "&quot;Return the formatters collection.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Parsers\\Parser", "fromLink": "Lincable/Parsers/Parser.html", "link": "Lincable/Parsers/Parser.html#method_getContainer", "name": "Lincable\\Parsers\\Parser::getContainer", "doc": "&quot;Return the containter instance.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Parsers\\Parser", "fromLink": "Lincable/Parsers/Parser.html", "link": "Lincable/Parsers/Parser.html#method_setContainer", "name": "Lincable\\Parsers\\Parser::setContainer", "doc": "&quot;Set the new container instance.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Parsers\\Parser", "fromLink": "Lincable/Parsers/Parser.html", "link": "Lincable/Parsers/Parser.html#method_findFormatter", "name": "Lincable\\Parsers\\Parser::findFormatter", "doc": "&quot;Return the first formatter that matches the option name.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Parsers\\Parser", "fromLink": "Lincable/Parsers/Parser.html", "link": "Lincable/Parsers/Parser.html#method_shouldParse", "name": "Lincable\\Parsers\\Parser::shouldParse", "doc": "&quot;Determine wheter the parameter should be parsed.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Parsers\\Parser", "fromLink": "Lincable/Parsers/Parser.html", "link": "Lincable/Parsers/Parser.html#method_runForParameter", "name": "Lincable\\Parsers\\Parser::runForParameter", "doc": "&quot;Run the parser for the parameter.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Parsers\\Parser", "fromLink": "Lincable/Parsers/Parser.html", "link": "Lincable/Parsers/Parser.html#method_callFormatter", "name": "Lincable\\Parsers\\Parser::callFormatter", "doc": "&quot;Resolve the formatter call using the container with the array parameters.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Parsers\\Parser", "fromLink": "Lincable/Parsers/Parser.html", "link": "Lincable/Parsers/Parser.html#method_resolveFormatter", "name": "Lincable\\Parsers\\Parser::resolveFormatter", "doc": "&quot;Resolve a formatter to a container callable.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Parsers\\Parser", "fromLink": "Lincable/Parsers/Parser.html", "link": "Lincable/Parsers/Parser.html#method_isParameterDynamic", "name": "Lincable\\Parsers\\Parser::isParameterDynamic", "doc": "&quot;Determine wheter the parameter is dynamic.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Parsers\\Parser", "fromLink": "Lincable/Parsers/Parser.html", "link": "Lincable/Parsers/Parser.html#method_getMatches", "name": "Lincable\\Parsers\\Parser::getMatches", "doc": "&quot;Return the matches.&quot;"},
            
            {"type": "Class", "fromName": "Lincable\\Providers", "fromLink": "Lincable/Providers.html", "link": "Lincable/Providers/MediaManagerServiceProvider.html", "name": "Lincable\\Providers\\MediaManagerServiceProvider", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Lincable\\Providers\\MediaManagerServiceProvider", "fromLink": "Lincable/Providers/MediaManagerServiceProvider.html", "link": "Lincable/Providers/MediaManagerServiceProvider.html#method_boot", "name": "Lincable\\Providers\\MediaManagerServiceProvider::boot", "doc": "&quot;Bootstrap any application services.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Providers\\MediaManagerServiceProvider", "fromLink": "Lincable/Providers/MediaManagerServiceProvider.html", "link": "Lincable/Providers/MediaManagerServiceProvider.html#method_register", "name": "Lincable\\Providers\\MediaManagerServiceProvider::register", "doc": "&quot;Register bindings in the container.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\Providers\\MediaManagerServiceProvider", "fromLink": "Lincable/Providers/MediaManagerServiceProvider.html", "link": "Lincable/Providers/MediaManagerServiceProvider.html#method_provides", "name": "Lincable\\Providers\\MediaManagerServiceProvider::provides", "doc": "&quot;Get the services provided by the provider.&quot;"},
            
            {"type": "Class", "fromName": "Lincable", "fromLink": "Lincable.html", "link": "Lincable/UrlCompiler.html", "name": "Lincable\\UrlCompiler", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Lincable\\UrlCompiler", "fromLink": "Lincable/UrlCompiler.html", "link": "Lincable/UrlCompiler.html#method___construct", "name": "Lincable\\UrlCompiler::__construct", "doc": "&quot;Create a new class instance.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\UrlCompiler", "fromLink": "Lincable/UrlCompiler.html", "link": "Lincable/UrlCompiler.html#method_compile", "name": "Lincable\\UrlCompiler::compile", "doc": "&quot;Compile a given url through the parser.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\UrlCompiler", "fromLink": "Lincable/UrlCompiler.html", "link": "Lincable/UrlCompiler.html#method_parseDynamics", "name": "Lincable\\UrlCompiler::parseDynamics", "doc": "&quot;Get all dynamic parameters on url based on parser.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\UrlCompiler", "fromLink": "Lincable/UrlCompiler.html", "link": "Lincable/UrlCompiler.html#method_hasDynamics", "name": "Lincable\\UrlCompiler::hasDynamics", "doc": "&quot;Determine wheter the url has dynamic parameters.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\UrlCompiler", "fromLink": "Lincable/UrlCompiler.html", "link": "Lincable/UrlCompiler.html#method_parseUrlFragments", "name": "Lincable\\UrlCompiler::parseUrlFragments", "doc": "&quot;Return all url fragments.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\UrlCompiler", "fromLink": "Lincable/UrlCompiler.html", "link": "Lincable/UrlCompiler.html#method_buildUrlFragments", "name": "Lincable\\UrlCompiler::buildUrlFragments", "doc": "&quot;Build an url from array fragments.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\UrlCompiler", "fromLink": "Lincable/UrlCompiler.html", "link": "Lincable/UrlCompiler.html#method_setParser", "name": "Lincable\\UrlCompiler::setParser", "doc": "&quot;Set the parser used on compiler.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\UrlCompiler", "fromLink": "Lincable/UrlCompiler.html", "link": "Lincable/UrlCompiler.html#method_getParser", "name": "Lincable\\UrlCompiler::getParser", "doc": "&quot;Get the current parser used on compiler.&quot;"},
            
            {"type": "Class", "fromName": "Lincable", "fromLink": "Lincable.html", "link": "Lincable/UrlConf.html", "name": "Lincable\\UrlConf", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Lincable\\UrlConf", "fromLink": "Lincable/UrlConf.html", "link": "Lincable/UrlConf.html#method___construct", "name": "Lincable\\UrlConf::__construct", "doc": "&quot;Create a new class instance.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\UrlConf", "fromLink": "Lincable/UrlConf.html", "link": "Lincable/UrlConf.html#method_has", "name": "Lincable\\UrlConf::has", "doc": "&quot;Determine if the given configuration value exists.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\UrlConf", "fromLink": "Lincable/UrlConf.html", "link": "Lincable/UrlConf.html#method_get", "name": "Lincable\\UrlConf::get", "doc": "&quot;Get the specified configuration value.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\UrlConf", "fromLink": "Lincable/UrlConf.html", "link": "Lincable/UrlConf.html#method_all", "name": "Lincable\\UrlConf::all", "doc": "&quot;Get all of the configuration items for the application.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\UrlConf", "fromLink": "Lincable/UrlConf.html", "link": "Lincable/UrlConf.html#method_set", "name": "Lincable\\UrlConf::set", "doc": "&quot;Set a given configuration value.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\UrlConf", "fromLink": "Lincable/UrlConf.html", "link": "Lincable/UrlConf.html#method_prepend", "name": "Lincable\\UrlConf::prepend", "doc": "&quot;Prepend a value onto an array configuration value.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\UrlConf", "fromLink": "Lincable/UrlConf.html", "link": "Lincable/UrlConf.html#method_push", "name": "Lincable\\UrlConf::push", "doc": "&quot;Push a value onto an array configuration value.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\UrlConf", "fromLink": "Lincable/UrlConf.html", "link": "Lincable/UrlConf.html#method_setModelsNamespace", "name": "Lincable\\UrlConf::setModelsNamespace", "doc": "&quot;Set the models namespace.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\UrlConf", "fromLink": "Lincable/UrlConf.html", "link": "Lincable/UrlConf.html#method_getModelFromKey", "name": "Lincable\\UrlConf::getModelFromKey", "doc": "&quot;Return the model namespace from key.&quot;"},
            
            {"type": "Class", "fromName": "Lincable", "fromLink": "Lincable.html", "link": "Lincable/UrlGenerator.html", "name": "Lincable\\UrlGenerator", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Lincable\\UrlGenerator", "fromLink": "Lincable/UrlGenerator.html", "link": "Lincable/UrlGenerator.html#method___construct", "name": "Lincable\\UrlGenerator::__construct", "doc": "&quot;Create a new class instance.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\UrlGenerator", "fromLink": "Lincable/UrlGenerator.html", "link": "Lincable/UrlGenerator.html#method_forModel", "name": "Lincable\\UrlGenerator::forModel", "doc": "&quot;Set the current model to generate the url.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\UrlGenerator", "fromLink": "Lincable/UrlGenerator.html", "link": "Lincable/UrlGenerator.html#method_guardModel", "name": "Lincable\\UrlGenerator::guardModel", "doc": "&quot;Guard the model setting verifying wheter the model is also configured\non url configuration.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\UrlGenerator", "fromLink": "Lincable/UrlGenerator.html", "link": "Lincable/UrlGenerator.html#method_setModelFormatters", "name": "Lincable\\UrlGenerator::setModelFormatters", "doc": "&quot;Add the formatters model parameters based on url dynamic parameters.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\UrlGenerator", "fromLink": "Lincable/UrlGenerator.html", "link": "Lincable/UrlGenerator.html#method_generate", "name": "Lincable\\UrlGenerator::generate", "doc": "&quot;Generate the url for the current model.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\UrlGenerator", "fromLink": "Lincable/UrlGenerator.html", "link": "Lincable/UrlGenerator.html#method_getRawUrl", "name": "Lincable\\UrlGenerator::getRawUrl", "doc": "&quot;Return the raw model url.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\UrlGenerator", "fromLink": "Lincable/UrlGenerator.html", "link": "Lincable/UrlGenerator.html#method_getModel", "name": "Lincable\\UrlGenerator::getModel", "doc": "&quot;Return the model instance.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\UrlGenerator", "fromLink": "Lincable/UrlGenerator.html", "link": "Lincable/UrlGenerator.html#method_getCompiler", "name": "Lincable\\UrlGenerator::getCompiler", "doc": "&quot;Return the compiler class instance.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\UrlGenerator", "fromLink": "Lincable/UrlGenerator.html", "link": "Lincable/UrlGenerator.html#method_getParsers", "name": "Lincable\\UrlGenerator::getParsers", "doc": "&quot;Return the collection parsers.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\UrlGenerator", "fromLink": "Lincable/UrlGenerator.html", "link": "Lincable/UrlGenerator.html#method_getAvailableParsers", "name": "Lincable\\UrlGenerator::getAvailableParsers", "doc": "&quot;Return the collection with available parsers for model.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\UrlGenerator", "fromLink": "Lincable/UrlGenerator.html", "link": "Lincable/UrlGenerator.html#method_getUrlConf", "name": "Lincable\\UrlGenerator::getUrlConf", "doc": "&quot;Return the model url configuration.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\UrlGenerator", "fromLink": "Lincable/UrlGenerator.html", "link": "Lincable/UrlGenerator.html#method_setParameterResolver", "name": "Lincable\\UrlGenerator::setParameterResolver", "doc": "&quot;Set the function to resolve parameter formatter.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\UrlGenerator", "fromLink": "Lincable/UrlGenerator.html", "link": "Lincable/UrlGenerator.html#method_withParameterResolver", "name": "Lincable\\UrlGenerator::withParameterResolver", "doc": "&quot;Set the function to resolve parameter formatter globally.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\UrlGenerator", "fromLink": "Lincable/UrlGenerator.html", "link": "Lincable/UrlGenerator.html#method_setCompiler", "name": "Lincable\\UrlGenerator::setCompiler", "doc": "&quot;Set the compiler class instance.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\UrlGenerator", "fromLink": "Lincable/UrlGenerator.html", "link": "Lincable/UrlGenerator.html#method_filterDynamicParameters", "name": "Lincable\\UrlGenerator::filterDynamicParameters", "doc": "&quot;Return the filtered dynamic parameters to apply as a formatter\non the parsers classes.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\UrlGenerator", "fromLink": "Lincable/UrlGenerator.html", "link": "Lincable/UrlGenerator.html#method_injectFormatterToAvailableParsers", "name": "Lincable\\UrlGenerator::injectFormatterToAvailableParsers", "doc": "&quot;Add the formatter for the parameters on the parsers based on current model url.&quot;"},
                    {"type": "Method", "fromName": "Lincable\\UrlGenerator", "fromLink": "Lincable/UrlGenerator.html", "link": "Lincable/UrlGenerator.html#method_parseAvailableParsers", "name": "Lincable\\UrlGenerator::parseAvailableParsers", "doc": "&quot;Generate the available parsers for the model.&quot;"},
            
            
                                        // Fix trailing commas in the index
        {}
    ];

    /** Tokenizes strings by namespaces and functions */
    function tokenizer(term) {
        if (!term) {
            return [];
        }

        var tokens = [term];
        var meth = term.indexOf('::');

        // Split tokens into methods if "::" is found.
        if (meth > -1) {
            tokens.push(term.substr(meth + 2));
            term = term.substr(0, meth - 2);
        }

        // Split by namespace or fake namespace.
        if (term.indexOf('\\') > -1) {
            tokens = tokens.concat(term.split('\\'));
        } else if (term.indexOf('_') > 0) {
            tokens = tokens.concat(term.split('_'));
        }

        // Merge in splitting the string by case and return
        tokens = tokens.concat(term.match(/(([A-Z]?[^A-Z]*)|([a-z]?[^a-z]*))/g).slice(0,-1));

        return tokens;
    };

    root.Sami = {
        /**
         * Cleans the provided term. If no term is provided, then one is
         * grabbed from the query string "search" parameter.
         */
        cleanSearchTerm: function(term) {
            // Grab from the query string
            if (typeof term === 'undefined') {
                var name = 'search';
                var regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
                var results = regex.exec(location.search);
                if (results === null) {
                    return null;
                }
                term = decodeURIComponent(results[1].replace(/\+/g, " "));
            }

            return term.replace(/<(?:.|\n)*?>/gm, '');
        },

        /** Searches through the index for a given term */
        search: function(term) {
            // Create a new search index if needed
            if (!bhIndex) {
                bhIndex = new Bloodhound({
                    limit: 500,
                    local: searchIndex,
                    datumTokenizer: function (d) {
                        return tokenizer(d.name);
                    },
                    queryTokenizer: Bloodhound.tokenizers.whitespace
                });
                bhIndex.initialize();
            }

            results = [];
            bhIndex.get(term, function(matches) {
                results = matches;
            });

            if (!rootPath) {
                return results;
            }

            // Fix the element links based on the current page depth.
            return $.map(results, function(ele) {
                if (ele.link.indexOf('..') > -1) {
                    return ele;
                }
                ele.link = rootPath + ele.link;
                if (ele.fromLink) {
                    ele.fromLink = rootPath + ele.fromLink;
                }
                return ele;
            });
        },

        /** Get a search class for a specific type */
        getSearchClass: function(type) {
            return searchTypeClasses[type] || searchTypeClasses['_'];
        },

        /** Add the left-nav tree to the site */
        injectApiTree: function(ele) {
            ele.html(treeHtml);
        }
    };

    $(function() {
        // Modify the HTML to work correctly based on the current depth
        rootPath = $('body').attr('data-root-path');
        treeHtml = treeHtml.replace(/href="/g, 'href="' + rootPath);
        Sami.injectApiTree($('#api-tree'));
    });

    return root.Sami;
})(window);

$(function() {

    // Enable the version switcher
    $('#version-switcher').change(function() {
        window.location = $(this).val()
    });

    
        // Toggle left-nav divs on click
        $('#api-tree .hd span').click(function() {
            $(this).parent().parent().toggleClass('opened');
        });

        // Expand the parent namespaces of the current page.
        var expected = $('body').attr('data-name');

        if (expected) {
            // Open the currently selected node and its parents.
            var container = $('#api-tree');
            var node = $('#api-tree li[data-name="' + expected + '"]');
            // Node might not be found when simulating namespaces
            if (node.length > 0) {
                node.addClass('active').addClass('opened');
                node.parents('li').addClass('opened');
                var scrollPos = node.offset().top - container.offset().top + container.scrollTop();
                // Position the item nearer to the top of the screen.
                scrollPos -= 200;
                container.scrollTop(scrollPos);
            }
        }

    
    
        var form = $('#search-form .typeahead');
        form.typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            name: 'search',
            displayKey: 'name',
            source: function (q, cb) {
                cb(Sami.search(q));
            }
        });

        // The selection is direct-linked when the user selects a suggestion.
        form.on('typeahead:selected', function(e, suggestion) {
            window.location = suggestion.link;
        });

        // The form is submitted when the user hits enter.
        form.keypress(function (e) {
            if (e.which == 13) {
                $('#search-form').submit();
                return true;
            }
        });

    
});


