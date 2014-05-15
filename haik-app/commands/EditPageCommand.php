<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class EditPageCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'edit:page';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Edit page directly.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
	    $default_page = Config::get('haik.page.default');
		$pagename = $this->argument('page');
		if ( ! $pagename)
		{
    		$pagename = $this->ask('What page name do you want to edit? ['.$default_page.']: ');
		}
		if ( ! $pagename)
		{
    		$pagename = $default_page;
		}
		$this->info("Edit page: $pagename");

        $page = Page::where('name', $pagename)->first();
        if ($page === null)
        {
            $this->error('Page cannot find: ' . $pagename);
            return 1;
        }

        $body = $page->body;

        $tmp_path = storage_path() . '/cache/artisan_edit_page_' . time();
        $fp = fopen($tmp_path, 'w');
        fwrite($fp, $body);
        fclose($fp);

        shell_exec("vi $tmp_path > /dev/tty < /dev/tty");

        $data = file_get_contents($tmp_path);

        $encode = mb_detect_encoding($data, "UTF-8, UTF-7, ASCII, EUC-JP,SJIS, eucJP-win, SJIS-win, JIS, ISO-2022-JP");
        if ($encode)
        {
            $data = mb_convert_encoding($data, 'UTF-8', $encode);
        }
        else
        {
            $this->error('Cannot detect page content charset.');
            $this->comment('Page editing fault.');
            $this->info('Input text is saved to: ' . $tmp_path);
            return 1;
        }

        unlink($tmp_path);

        $page->body = $data;
        $page->save();

        $this->info('Page successfully saved!');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('page', InputArgument::OPTIONAL, 'Page name to edit.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
//			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
