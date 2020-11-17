module.exports = {
  runtimeCompiler: true,
  filenameHashing: false,
  chainWebpack: (config) => {
	  config.resolve.symlinks(false)
	}
}