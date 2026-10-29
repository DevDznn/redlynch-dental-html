<?php
/**
 * Builds wordpress-theme/redlynch-dental.zip with the theme folder at the root of the archive.
 *
 * Usage (from the repository root):  php wordpress-theme/tools/package.php [file-name.zip]
 * With a file name, writes that ZIP in wordpress-theme/ instead (e.g. redlynch-dental-1.2.0.zip).
 * Needs PHP's zip extension. Paths inside the ZIP always use forward slashes.
 */

$base = dirname( __DIR__ );
$src  = $base . DIRECTORY_SEPARATOR . 'redlynch-dental';
$dest = $base . DIRECTORY_SEPARATOR . ( isset( $argv[1] ) ? basename( $argv[1] ) : 'redlynch-dental.zip' );
$skip = '#(^|/)(\.git|\.DS_Store|Thumbs\.db|node_modules|\.vscode)(/|$)#';

if ( ! class_exists( 'ZipArchive' ) ) {
	fwrite( STDERR, "PHP zip extension missing.\n" );
	exit( 1 );
}
if ( file_exists( $dest ) ) {
	unlink( $dest );
}
$zip = new ZipArchive();
if ( true !== $zip->open( $dest, ZipArchive::CREATE ) ) {
	fwrite( STDERR, "Cannot create $dest\n" );
	exit( 1 );
}
$zip->addEmptyDir( 'redlynch-dental' );
$files = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $src, FilesystemIterator::SKIP_DOTS ), RecursiveIteratorIterator::SELF_FIRST );
$count = 0;
foreach ( $files as $file ) {
	$rel = str_replace( '\\', '/', substr( $file->getPathname(), strlen( $src ) + 1 ) );
	if ( preg_match( $skip, $rel ) ) {
		continue;
	}
	$name = 'redlynch-dental/' . $rel;
	if ( $file->isDir() ) {
		$zip->addEmptyDir( $name );
	} else {
		$zip->addFile( $file->getPathname(), $name );
		++$count;
	}
}
$zip->close();
printf( "Wrote %s (%d files, %s KB)\n", basename( $dest ), $count, number_format( filesize( $dest ) / 1024 ) );
