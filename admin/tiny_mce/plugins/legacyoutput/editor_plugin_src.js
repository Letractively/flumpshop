
(function(tinymce){tinymce.onAddEditor.addToTop(function(tinymce,editor){editor.settings.inline_styles=false;});tinymce.create('tinymce.plugins.LegacyOutput',{init:function(editor){editor.onInit.add(function(){var alignElements='p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img',fontSizes=tinymce.explode(editor.settings.font_size_style_values),serializer=editor.serializer;editor.formatter.register({alignleft:{selector:alignElements,attributes:{align:'left'}},aligncenter:{selector:alignElements,attributes:{align:'center'}},alignright:{selector:alignElements,attributes:{align:'right'}},alignfull:{selector:alignElements,attributes:{align:'full'}},bold:{inline:'b'},italic:{inline:'i'},underline:{inline:'u'},strikethrough:{inline:'strike'},fontname:{inline:'font',attributes:{face:'%value'}},fontsize:{inline:'font',attributes:{size:function(vars){return tinymce.inArray(fontSizes,vars.value)+1;}}},forecolor:{inline:'font',styles:{color:'%value'}},hilitecolor:{inline:'font',styles:{backgroundColor:'%value'}}});serializer._setup();tinymce.each('b,i,u,strike'.split(','),function(name){var rule=serializer.rules[name];if(!rule)
serializer.addRules(name);});if(!serializer.rules["font"])
serializer.addRules("font[face|size|color|style]");tinymce.each(alignElements.split(','),function(name){var rule=serializer.rules[name],found;if(rule){tinymce.each(rule.attribs,function(name,attr){if(attr.name=='align'){found=true;return false;}});if(!found)
rule.attribs.push({name:'align'});}});editor.onNodeChange.add(function(editor,control_manager){var control,fontElm,fontName,fontSize;fontElm=editor.dom.getParent(editor.selection.getNode(),'font');if(fontElm){fontName=fontElm.face;fontSize=fontElm.size;}
if(control=control_manager.get('fontselect')){control.select(function(value){return value==fontName;});}
if(control=control_manager.get('fontsizeselect')){control.select(function(value){var index=tinymce.inArray(fontSizes,value.fontSize);return index+1==fontSize;});}});});},getInfo:function(){return{longname:'LegacyOutput',author:'Moxiecode Systems AB',authorurl:'http://tinymce.moxiecode.com',infourl:'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/legacyoutput',version:tinymce.majorVersion+"."+tinymce.minorVersion};}});tinymce.PluginManager.add('legacyoutput',tinymce.plugins.LegacyOutput);})(tinymce);