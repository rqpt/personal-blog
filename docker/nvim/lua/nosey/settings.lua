local g          = vim.g
local o          = vim.opt

o.numberwidth    = 3
o.shiftwidth     = 2
o.tabstop        = 2
o.softtabstop    = 2
o.scrolloff      = 20
o.sidescrolloff  = 15
o.updatetime     = 50
o.conceallevel   = 2
o.foldlevel      = 99
o.cmdheight      = 1
o.colorcolumn    = '80'

o.cursorline     = false
o.expandtab      = true
o.smartindent    = true
o.relativenumber = true
o.termguicolors  = true
o.ignorecase     = true
o.smartcase      = true
o.wrap           = false
o.breakindent    = true
o.list           = true
o.splitbelow     = true
o.splitright     = true
o.confirm        = true
o.swapfile       = false
o.undofile       = true
o.backup         = true
o.hlsearch       = false
o.incsearch      = true
o.showmode       = false
o.lazyredraw     = false

o.wildmode       = 'longest:full,full'
o.signcolumn     = "auto:1-2"
o.completeopt    = 'menuone,longest,preview'
o.clipboard      = "unnamedplus"
o.statuscolumn   = "%=%{v:virtnum < 1 ? (v:relnum ? v:relnum : v:lnum < 10 ? v:lnum . '  ' : v:lnum) : ''}%=%s"
o.backupdir:remove('.')
o.isfname:append("@-@")
o.undodir   = os.getenv("HOME") .. "/.vim/undodir"
o.listchars = { tab = '▸ ', trail = '·' }
o.fillchars:append({ eob = ' ' })

-- disable builtin plugins
g.loaded_python3_provider  = 1
g.loaded_python_provider   = 1
g.loaded_node_provider     = 1
g.loaded_ruby_provider     = 1
g.loaded_perl_provider     = 1
g.loaded_2html_plugin      = 1
g.loaded_getscript         = 1
g.loaded_getscriptPlugin   = 1
g.loaded_gzip              = 1
g.loaded_tar               = 1
g.loaded_tarPlugin         = 1
g.loaded_rrhelper          = 1
g.loaded_vimball           = 1
g.loaded_vimballPlugin     = 1
g.loaded_zip               = 1
g.loaded_zipPlugin         = 1
g.loaded_tutor             = 1
g.loaded_rplugin           = 1
g.loaded_logiPat           = 1
g.loaded_netrw             = 1
g.loaded_netrwPlugin       = 1
g.loaded_netrwSettings     = 1
g.loaded_netrwFileHandlers = 1
g.loaded_syntax            = 1
g.loaded_synmenu           = 1
g.loaded_optwin            = 1
g.loaded_compiler          = 1
g.loaded_bugreport         = 1
g.loaded_ftplugin          = 1
g.did_load_ftplugin        = 1
g.did_indent_on            = 1
g.rustfmt_autosave         = 1

vim.cmd([[
filetype plugin indent on
syntax enable
]])
