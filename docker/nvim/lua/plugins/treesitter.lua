return {
    "nvim-treesitter/nvim-treesitter",
    dependencies = {
        "JoosepAlviste/nvim-ts-context-commentstring",
    },
    build = function()
        require("nvim-treesitter.install").update({ with_sync = true })
    end,
    opts = {
        disable = function(lang, buf)
            local max_filesize = 100 * 1024 -- 100 KB
            local ok, stats = pcall(vim.loop.fs_stat, vim.api.nvim_buf_get_name(buf))
            if ok and stats and stats.size > max_filesize then
                return true
            end
        end,
        ensure_installed = {
            "all",
        },
        auto_install = true,
        highlight = {
            enable = true,
        },
        indent = {
            enable = true,
        },
        endwise = {
            enable = true,
        },
    },
}
