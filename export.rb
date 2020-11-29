require "jekyll-import";
JekyllImport::Importers::WordpressDotCom.run({
  "source" => "/home/penguin/tmp/russianpenguin.wordpress.com-2020-11-29-13_23_37/wordpress.2020-11-29.001.xml",
  "no_fetch_images" => false,
  "assets_folder" => "assets/images"
})
