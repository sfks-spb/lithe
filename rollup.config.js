import coffeescript from 'rollup-plugin-coffee-script';
import resolve from '@rollup/plugin-node-resolve';
import commonjs from '@rollup/plugin-commonjs';

export default {
  input: './assets/coffee/main.coffee',
  output: {
    file: './assets/js/Lithe-1.0.1/bundle.js',
    format: 'cjs'
  },
  plugins: [
    coffeescript(),
    resolve(),
    commonjs()
  ]
};