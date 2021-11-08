import coffeescript from 'rollup-plugin-coffee-script';
import resolve from '@rollup/plugin-node-resolve';
import commonjs from '@rollup/plugin-commonjs';

export default [
    {
      input: './assets/coffee/frontend/main.coffee',
      output: {
        file: './assets/js/Lithe@1.0.2/bundle.js',
        format: 'iife'
      },
      plugins: [
        coffeescript(),
        resolve(),
        commonjs()
      ]
    },
    {
      input: './assets/coffee/admin/main.coffee',
      output: {
        file: './assets/js/Lithe@1.0.2/admin.js',
        format: 'iife'
      },
      plugins: [
        coffeescript(),
        resolve(),
        commonjs()
      ]
    }
];