 O-blog 2.5 beta 到 O-blog 2.5 正式版 升级程序
 Support : http://www.phpBlog.cn
 =============================================

 *** 升级之前，建议您备份原来的文件和数据 ***

 升级方法：

 1. 首先确认您原来的 O-blog 版本为 2.5 beta

 2. 将 O-blog 2.5 正式版的文件上传到您的服务器中，
    并覆盖了原来的文件。注意：admin/mysql.php不要覆盖!

 3. 确认 upgrade_25beta.php 文件传到了 O-blog 的根目录

 4.确认以下文件(夹)是可写的(777) (WINDOWS 主机跳过这一步)
   ./
   ./cache
   ./archives
   .bak
   ./uploadfiles

 5.运行 upgrade_25beta.php 文件进行升级

 6.升级成功后，删除 upgrade_25beta.php 文件