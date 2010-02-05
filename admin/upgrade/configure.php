<html>
<body>
<p>Hello, this is the Flumpshop Update configuration utility. This system is for use by the Flumpshop development team only, and has absolutely no use to the regular user.</p>
<p>This form will return the Update object for placing on the update server.
<form action='doConfigure.php' method="post" id="form">
Version: <input type="text" name="version" id="version" /><br />
Previous Revision: <input type="text" name="prevRevision" id="prevRevision" /><br />
New Conf Vars (Tree): <textarea name="confVarsTree" id="confVarsTree"></textarea><br />
New Conf Vars (Node): <textarea name="confVarsNode" id="confVarsNode"></textarea><br />
New Conf Vars (Default): <textarea name="confVarsVal" id="confVarsVal"></textarea><br />
New Conf Vars (Name): <textarea name="confVarsName" id="confVarsName"></textarea><br />
<input type="submit" onClick="this.disabled = true; document.getElementById('form').submit();" />
</form>
<p>This form will automatically generate an Upgrade package based on the Flumpshop instance it resides in. This can then be placed in the Flumpshop downloads, and, once the versions.txt file has been updated, will automatically be detected by all other Flumpshop instances, which will then prompt users to update.</p>
</body>
</html>