<?php
	
	/**
	 * @version 1.0b
	 * @author Ismael Miguel
	 * 
	 * MIME type class to detect and return MIME types or extensions
	 * 
	 * @method array fromExt(string $ext, string|null $hint)
	 * @method array fromMIME(string $mime)
	 *
	 */
	
	class MIMETypes {
		
		/**
		 * @var string Contains the default type when none is found
		 * @access protected
		 */
		static private $default='application/octet-stream';
		
		/**
		 * @var array Contains all the mime types and extensions
		 * @access protected
		 */
		static private $mimes=array(
			'text'=>array(
				'plain'=>array(
					'php','php5','php4','php3','inc','sphp','phps','phtml',
					'htm','html',
					'txt'
				)
			),
			'audio'=>array(
				'aac'=>array('aac'),
				'midi'=>array('mid','midi'),
				'mp4'=>array('mp4','m4a'),
				'mpeg'=>array('m2a','mp1','mp2','mp3','mp4','mpg','mpeg','mpa','mpga'),
				'ogg'=>array('oga','ogg'),
				'wav'=>array('wav','wave','pcm'),
				'webm'=>array('webm'),
				'x-mpequrl'=>array('m3u')
			),
			'video'=>array(
				'mp4'=>array('mp4','m4v','m1v','m2v'),
				'ogg'=>array('ogv'),
				'webm'=>array('webm')
			)
		);
		
		/**
		 * @var array Contains the last accessed contents and comes pre-initialized with some extensions and mime types that didn't matched
		 * @access protected
		 */		
		static private $cache=array(
			'ext'=>array(
				'txt'=>array('plain/text')
			),
			'mime'=>array(
				'*/*'=>array()
			)
		);
		
		/**
		 * Creates a list of matching mime types for that file extension
		 * @param string $ext The extension to look for
		 * @param string|null $hint Hint to reduce the search load for only that mime "group"<br/>
		 *    *WARNING*: Setting this parameter will ignore the cache!
		 * @return array An array with the multiple mime types
		 */
		static function fromExt( $ext, $hint=null )
		{
			//this will have all matched mime types
			$exts = array();
			
			//clean up the $ext var, to hold nothing but an extension
			$ext = preg_replace( '@^(?:.*\.)*([^.\\]+)$@', '$1', $ext );
			
			if( func_num_args() > 1 )
			{
				$hint = strtolower( trim($hint) );
				
				foreach( self::$mimes[$hint] as $mime=>$types)
				{
					if( in_array( $ext, $types) )
					{
						$exts[] = $hint . '/' . $mime;
					}
				}
			}
			else if( self::$cache['ext'][$ext] )
			{
				return self::$cache['ext'][$ext];
			}
			else
			{
				foreach( self::$mimes as $mime=>$mimes)
				{
					foreach( $mimes as $type=>$types)
					{
						if( in_array( $ext, $list) )
						{
							$exts[] = $mime . '/' . $type;
						}
					}
				}
				
				if(!$exts)
				{
					$exts=array( self::$default );
				}
			}
			
			return self::$cache['ext'][$ext] = $exts;
		}
		
		/**
		 * Creates a list of matching extensions for that mime type
		 * @param string $mime The extension to look for
		 * @return array An array with the multiple extensions
		 */
		static function fromMIME( $mime )
		{
			//'clean' blindly-exploded mime type 
			$mime_c=explode('/',$mime,2);
			
			if( self::$cache['mime'][$mime] )
			{
				return array(); //self::$cache['mime'][$mime];
			}
			else if( !self::$mimes[$mime_c[0]])
			{
				return self::$mimes[$mime_c[0]];
			}
			else
			{
				return self::$cache['mime'][$mime] = array();
			}
		}
	}
	
?>
